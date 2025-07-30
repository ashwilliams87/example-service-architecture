<?php

namespace Lan\Services\Security;

use Ebs\Core\Model\Document;
use Ebs\Model\Api_User_Article_Key;
use Ebs\Model\Api_User_Book_Key;
use Ebs\Model\Book;
use Ebs\Model\Journal_Article;
use Ebs\Security\Ebs;
use Ice\Core\Exception;
use Ice\Exception\Config_Error;
use Ice\Exception\Console_Run;
use Ice\Exception\Error;
use Ice\Exception\FileNotFound;
use Ice\Helper\Console;
use Ice\Helper\Directory;
use Ice\Helper\Json;
use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\Contracts\Services\Security\CryptServiceInterface;
use Lan\Contracts\Services\Security\DocumentCryptServiceInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\DataTypes\FileTypes\AudioFileType;
use Lan\DataTypes\FileTypes\EpubFileType;
use Lan\DataTypes\FileTypes\PdfFileType;
use Lan\DataTypes\FileTypes\TextFileType;
use Lan\Helpers\DocumentHelper;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class DocumentCryptService implements DocumentCryptServiceInterface
{
    const PYTHON_INTERPRETER = 'python3';

    const KEY_CRYPTER_PASSWORD = 'eDv4Sw3DdSakjGEP';
    const KEY_CRYPTER_IV = '00000000000000000000000000000000';
    const DATA_CRYPTER_IV = '00000000000000000000000000000000';

    public function __construct(
        private SecurityServiceInterface $securityService,
        private CryptServiceInterface    $cryptService
    )
    {
    }

    /**
     * @param Document $document
     * @return array
     * @throws \Exception
     */
    public function createKey(Document $document): array
    {
        $map = $this->getMap(get_class($document));

        $documentKey = (int)$document->getPkValue();
        $user_id = (int)$this->securityService->getUser()
            ->getPkValue();

        $key = substr(sha1($documentKey . $user_id . (time() + 24 * 3) . 'key'), 5, 16);

        $key = $this->cryptService->encrypt(
            stringToCrypt: $key,
            key: self::KEY_CRYPTER_PASSWORD,
            iv: self::KEY_CRYPTER_IV,
        );

        $code = random_int(10, 100);

        $key_in_array = [$code];

        for ($i = 0, $iMax = strlen($key); $i < $iMax; $i++) {
            $key_in_array[] = (ord($key[$i]) + $code) * 3;
        }

        $key_in_array = array_reverse($key_in_array);

        $map['modelClass']::createQueryBuilder()
            ->getInsertQuery(
                [
                    'user_id' => $user_id,
                    $map['field'] => $documentKey,
                    'key' => Json::encode($key_in_array)
                ],
                true
            )->getQueryResult();

        return $key_in_array;
    }

    /**
     * @param $documentClass
     * @return string[]
     */
    private function getMap(string $documentClass): array
    {
        return [
            Book::class => [
                'modelClass' => Api_User_Book_Key::class,
                'field' => 'book_id'
            ],
            Journal_Article::class => [
                'modelClass' => Api_User_Article_Key::class,
                'field' => 'article_id'
            ]
        ][$documentClass];
    }

    /**
     * @param Document $document
     * @return string
     * @throws Error
     * @throws FileNotFound
     * @throws Exception
     */
    public function getMeta(Document $document): string
    {
        $expiredDate = DocumentHelper::getExpiredDate($document, $this->securityService->getUser());

        $meta = [
            'date' => $expiredDate === '' ? null : $expiredDate,
            'hasEpub' => (bool)$document->getEpubPath(),
            'hasPdf' => (bool)$document->getPdfPath(),
            'hasSyntex' => (bool)$document->getSynthesizerPath(),
            'hasAudio' => (bool)$document->getAudioPath()
        ];

        $key = $this->getKey($document);

        if (!$key) {
            throw new Error('Key not found');
        }

        return $this->cryptService->encrypt(
            stringToCrypt: Json::encode($meta),
            key: $key,
            iv: self::DATA_CRYPTER_IV,
        );
    }


    /**
     * @param Document $document
     * @param Ebs $security
     * @return false|string|null
     * @throws Error
     * @throws FileNotFound
     */
    private function getKey(Document $document): string
    {
        $map = $this->getMap(get_class($document));

        $documentKey = (int)$document->getPkValue();
        $user_id = (int)$this->securityService->getUser()
            ->getPkValue();

        /* @var Api_User_Book_Key|Api_User_Article_Key $map['modelClass'] */
        $key_in_array = $map['modelClass']::getSelectQuery('key', ['user_id' => $user_id, $map['field'] => $documentKey])->getValue();

        if (!$key_in_array) {
             return '';
        }

        $key_in_array = Json::decode($key_in_array);

        $key_in_array = array_reverse($key_in_array);

        $code = array_shift($key_in_array);

        $key = '';

        foreach ($key_in_array as $k) {
            $key .= chr(($k / 3) - $code);
        }

        return $this->cryptService->decrypt(
            encryptedString: $key,
            key: self::KEY_CRYPTER_PASSWORD,
            iv: self::KEY_CRYPTER_IV
        );
    }

    /**
     * @param Document $document
     * @param string $file_in
     * @param string $fileType
     * @param bool $isDirectory
     * @return string
     * @throws Config_Error
     * @throws Console_Run
     * @throws Error
     * @throws Exception
     * @throws FileNotFound
     */
    private function encryptFileOrDirectory(
        Document          $document,
        string            $file_in,
        FileTypeInterface $fileType,
        bool              $isDirectory = false
    ): string
    {
        $book_id = (int)$document->getPkValue();

        $dir_out = Directory::get(getTempDir() . '/crypt/' . $this->securityService->getUser()->getPkValue() . '/' . $fileType->getName());

        $key = $this->getKey($document);

        if (!$key) {
            throw new Error('Key not found');
        }

        if ($isDirectory) {
            $zip = new ZipArchive();
            $zipFile = $dir_out . $book_id . '.' . $fileType->getName() . '.zip';
            $zip->open($zipFile, ZipArchive::CREATE);

            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($file_in), RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
                if (!$file->isDir()) {
                    $zip->addFile($file->getRealPath(), 'io/' . $file->getFilename());
                }
            }
            $zip->close();

            $file_in = $zipFile;
        }

        $file_out = $dir_out . $book_id . '.' . $fileType->getName() . '.aes';

        Console::run(self::PYTHON_INTERPRETER . ' ' . getModuleDir() . 'bin/encription.py ' . $key . ' ' . $file_in . ' ' . $file_out);

        if ($isDirectory) {
            unlink($file_in);
        }

        return $file_out;
    }

    /**
     * @param Document $document
     * @param FileTypeInterface $fileType
     * @return string
     * @throws Error
     * @throws \Exception
     */
    public function getEncryptedFilePath(Document $document, FileTypeInterface $fileType): string
    {
        ini_set('memory_limit', '2G');

        switch (get_class($fileType)) {
            case PdfFileType::class:
                $file_in = $document->getPdfPath();
                if (!is_file($file_in)) {
                    throw new Error('Pdf File Not Found.');
                }

                break;
            case EpubFileType::class:
                $file_in = $document->getEpubPath();

                if (!is_file($file_in)) {
                    throw new Error('Epub File Not Found.');
                }

                break;
            case TextFileType::class:
                $file_in = $document->getSynthesizerPath();

                if (!is_file($file_in)) {
                    throw new Error('Synthesizer File Not Found.');
                }

                break;
            case AudioFileType::class:
                $file_in = $document->getAudioPath();

                if (!is_dir($file_in)) {
                    throw new Error('Audio Dir Not Found.');
                }

                break;
            default:
                throw new Error(['Data type {$0} unknown', $fileType->getName()]);
        }

        $file_out = $this->encryptFileOrDirectory(
            document: $document,
            file_in: $file_in,
            fileType: $fileType,
            isDirectory: is_dir($file_in)
        );

        if (is_file($file_out)) {
            return $file_out;
        } else {
            throw new Error('Error while crypt file PDF.' . $file_in . ' File_out:' . $file_out . ' Document_id:' . $document->getPkValue() . '  user_id:' . $security->getUser()->getPkValue());
        }
    }
}

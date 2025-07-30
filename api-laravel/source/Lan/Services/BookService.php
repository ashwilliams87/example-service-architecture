<?php

namespace Lan\Services;

use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\Contracts\DTOs\Book\BookCardDTOInterface;
use Lan\Contracts\DTOs\Book\BookCardListResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentCipherKeyResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentMetaResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\DocumentDownloadResponseDTOInterface;
use Lan\Contracts\Repositories\BookRepositoryInterface;
use Lan\Contracts\Services\BookServiceInterface;
use Lan\Contracts\Services\Security\DocumentCryptServiceInterface;
use Lan\Contracts\Services\Security\DownloadProtectorServiceInterface;
use Lan\Contracts\Services\UserActivityLogServiceInterface;
use Lan\DataTypes\RequestResult\Error\NoAccessToResourceError;
use Lan\DataTypes\RequestResult\Error\NotAvailableForDownloadError;
use Lan\DataTypes\RequestResult\Error\ResourceNotFoundError;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Book\Responses\BookCardDTO;
use Lan\DTOs\Book\Responses\BookCardListResponseDTO;
use Lan\DTOs\Book\Responses\DocumentDownloadResponseDTO;
use Lan\DTOs\Document\DocumentCipherKeyResponseDTO;
use Lan\DTOs\Document\DocumentMetaResponseDTO;

class BookService implements BookServiceInterface
{
    public function __construct(
        private BookRepositoryInterface           $bookRepository,
        private DownloadProtectorServiceInterface $downloadProtectorService,
        private DocumentCryptServiceInterface     $documentCryptService,
        private UserActivityLogServiceInterface   $userActivityLogService,
    )
    {

    }

    public function getBooksFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): BookCardListResponseDTOInterface
    {
        $queryResult = $this->bookRepository->getBooksFromCatalog($requestDTO);
        return BookCardListResponseDTO::createFromIceQueryResultRows($queryResult);
    }

    public function getBook(DocumentIdRequestDTOInterface $requestDTO): BookCardDTOInterface
    {
        $book = $this->bookRepository->getBook($requestDTO->getId());

        if (!$book->getPk()) {
            return BookCardDTO::create();
        }
        return BookCardDTO::createFromIceModel($book);
    }

    public function createBookKey(DocumentIdRequestDTOInterface $bookRequestDTO): DocumentCipherKeyResponseDTOInterface
    {
        $book = $this->bookRepository->getBook($bookRequestDTO->getId());

        if (!$book->getPk()) {
            return DocumentCipherKeyResponseDTO::create(status: ResourceNotFoundError::create());
        }

        if (!$book->get('available')) {
            return DocumentCipherKeyResponseDTO::create(status: NoAccessToResourceError::create());
        }

        if (!$this->downloadProtectorService->isDownloadPossible($book)) {
            return DocumentCipherKeyResponseDTO::create(status: NotAvailableForDownloadError::create());
        }

        return DocumentCipherKeyResponseDTO::create(
            key: $this->documentCryptService->createKey($book),
            status: SuccessOk200::create()
        );
    }

    public function getBookMeta(DocumentIdRequestDTOInterface $requestDTO): DocumentMetaResponseDTOInterface
    {
        $book = $this->bookRepository->getBook($requestDTO->getId());

        if (!$book->getPk()) {
            return DocumentMetaResponseDTO::create(status: ResourceNotFoundError::create());
        }

        $this->userActivityLogService->logDocumentRead($book);

        if (!$this->downloadProtectorService->isDownloadPossible($book)) {
            return DocumentMetaResponseDTO::create(status: NotAvailableForDownloadError::create());
        }

        return DocumentMetaResponseDTO::create(
            meta: $this->documentCryptService->getMeta($book),
            status: SuccessOk200::create()
        );
    }

    public function getDownloadFilePath(
        DocumentIdRequestDTOInterface $requestDTO,
        FileTypeInterface             $fileType,
    ): DocumentDownloadResponseDTOInterface
    {
        $book = $this->bookRepository->getBook($requestDTO->getId());

        // В логике api-phalcon не было проверки на Not found. Пока не стал реализовывать, но имеет смысл в будущем реализовать
//        if(!$book->getPk()){
//            return BookMetaResponseDTO::create(status: new ResourceNotFoundError());
//        }

        $this->userActivityLogService->logDocumentRead($book);

        if (!$this->downloadProtectorService->isDownloadPossible($book)) {
            return DocumentDownloadResponseDTO::create(status: NotAvailableForDownloadError::create());
        }

        return DocumentDownloadResponseDTO::create(
            filePath: $this->documentCryptService->getEncryptedFilePath($book, $fileType),
            status: SuccessOk200::create()
        );
    }
}

<?php

namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ebs\Model\Book;
use Ice\Core\QueryResult;
use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\DataTypes\FileTypes\AudioFileType;
use Lan\DataTypes\FileTypes\EpubFileType;
use Lan\DataTypes\FileTypes\PdfFileType;
use Lan\DataTypes\FileTypes\TextFileType;
use Lan\DataTypes\RequestResult\Error\NoAccessToResourceError;
use Lan\DataTypes\RequestResult\Error\NotAvailableForDownloadError;
use Lan\DataTypes\RequestResult\Error\ResourceNotFoundError;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Book\Responses\BookCardDTO;
use Lan\DTOs\Book\Responses\BookCardListResponseDTO;
use Lan\DTOs\Book\Responses\DocumentDownloadResponseDTO;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;
use Lan\DTOs\Document\DocumentCipherKeyResponseDTO;
use Lan\DTOs\Document\DocumentIdRequestDTO;
use Lan\DTOs\Document\DocumentMetaResponseDTO;
use Lan\Repositories\BookRepository;
use Lan\Services\BookService;
use Lan\Services\Security\DocumentCryptService;
use Lan\Services\Security\DownloadProtectService;
use Lan\Services\UserActivityLogService;
use Tests\Support\UnitTester;

class BookServiceTest extends Unit
{
    protected array $fileTypesClassnames = [
        PdfFileType::class,
        EpubFileType::class,
        TextFileType::class,
        AudioFileType::class,
    ];

    protected UnitTester $tester;

    public function testGetBooksFromCatalog(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            categoryId: 945,
            subCategoryId: 0,
            publisherId: 0,
            limit: 20,
            offset: 0,
            syntex: 1,
            author: '',
            sortingField: 'author',
        );


        $queryRows = [];
        for ($i = 1; $i <= 50; $i++) {
            $queryRows[] = [
                "all_count" => 1,
                "expired" => "2024-04-23",
                "available" => 1,
                "id" => $i,
                "book_private" => 0,
                "description" => "Test Description " . $i,
                "year" => 2021,
                "book_expired" => "2024-04-23",
                "title" => "Test Book Title " . $i,
                "author" => "Test Author " . $i,
                "hasPdf" => "/book/905/2021/" . $i . "/" . $i . ".pdf",
                "hasEpub" => "/book/905/2021/" . $i . "/" . $i . ".epub",
                "hasAudio" => null,
                "hasSyntex" => "/book/905/2021/" . $i . "/" . $i . "_synth.epub",
                "synthesizer_editor" => null,
                "synthesizer_quality" => 1,
                "publisher" => "Test Publisher " . $i,
                "expired_date" => "2030-12-31",
                "access_id" => 1024670 + $i,
            ];
        }

        $repositoryQueryResultMock = $this->make(QueryResult::class, [
            'getRows' => Expected::once(function () use ($queryRows){
                return $queryRows;
            }),
            'getFoundRows' => Expected::never(),
        ]);

        $expectedQueryResultMock = $this->make(QueryResult::class, [
            'getRows' => Expected::once(function () use ($queryRows){
                return $queryRows;
            }),
            'getFoundRows' => Expected::never(),
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBooksFromCatalog' => Expected::once(function (DocumentsTypeRequestDTO $requestDTO) use ($repositoryQueryResultMock){
                return $repositoryQueryResultMock;
            }),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $this->make(DownloadProtectService::class),
            documentCryptService: $this->make(DocumentCryptService::class),
            userActivityLogService: $userActivityLogServiceMock,
        );

        $expectedServiceResult = BookCardListResponseDTO::createFromIceQueryResultRows($expectedQueryResultMock);

        $serviceResult = $bookService->getBooksFromCatalog($requestDTO);

        $this->assertInstanceOf(BookCardListResponseDTO::class, $serviceResult);
        $this->assertEquals($expectedServiceResult->toMobileScheme(), $serviceResult->toMobileScheme());
    }

    public function testGetBook(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);

        $book = Book::create([
            '/pk' => 1,
            'publisher__fk' => 1,
            'publish_year' => 2024,
            'authors' => 'Test Author',
            'isbn' => '123-4-567-89012-3',
            'edition' => '1st Edition',
            'pages' => 200,
            'synthesizer_path' => '/book/905/2021/183134/183134_synth.epub',
            'synthesizer_editor' => 'Test Editor',
            'synthesizer_quality' => 1,
            'pdf_path' => '/book/1/pdf_path',
            'epub_path' => '/book/1/epub_path',
            'file_path' => '/book/1/file_path',
            'audio_path' => '/book/1/audio_path',
            'book_name' => 'Test book',
            'access_date' => '2024-04-23',
            'book_active' => 1,
            'book_desc' => 'Test Description',
            'book_private' => 0,
            'available' => 0,
            'publisher_name' => 'Test Publisher',
            'expired' => '2024-04-23',
            'expired_date' => null,
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBook' => Expected::once(function () use ($book){
                return $book;
            }),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $this->make(DownloadProtectService::class),
            documentCryptService: $this->make(DocumentCryptService::class),
            userActivityLogService: $userActivityLogServiceMock,
        );

        $expectedResponseDto = BookCardDTO::createFromIceModel($book);
        $serviceResponseResult = $bookService->getBook($requestDTO);

        $this->assertInstanceOf(BookCardDTO::class, $serviceResponseResult);
        $this->assertEquals($expectedResponseDto->toMobileScheme(), $serviceResponseResult->toMobileScheme());
    }

    public function testGetBookNotFound(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 99999);

        $book = Book::create();
        $bookRepositoryMock = $this->createMock(BookRepository::class);
        $bookRepositoryMock->expects($this->once())
            ->method('getBook')
            ->with($this->equalTo($requestDTO->getId()))
            ->willReturn($book);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $this->make(DownloadProtectService::class),
            documentCryptService: $this->make(DocumentCryptService::class),
            userActivityLogService: $userActivityLogServiceMock,
        );
        $responseDto = $bookService->getBook($requestDTO);

        $this->assertInstanceOf(BookCardDTO::class, $responseDto);

        $this->assertEquals(true, $responseDto->isEmpty());
    }


    // ->createBookKey() tests

    public function testCreateBookKeyWithAvailableAndPossibleToDownloadBook(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);
        $expectedKeys = [264, 249, 327, 348, 330, 390, 411, 426, 336, 345, 390, 414];

        $bookMock = $this->make(Book::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
            'get' => Expected::once(function (string $bookGetKey) {
                return true;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::once(function (Book $book) {
                return true;
            }),
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBook' => Expected::once(function (int $bookId) use ($bookMock) {
                return $bookMock;
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'createKey' => Expected::once(function (Book $book) {
                return [264, 249, 327, 348, 330, 390, 411, 426, 336, 345, 390, 414];
            }),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $bookService->createBookKey($requestDTO);

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $responseDto);
        $this->assertEquals($expectedKeys, $responseDto->getKey());
        $this->assertEquals(SuccessOk200::create(), $responseDto->getHttpStatusResult());
        $this->assertEquals(false, $responseDto->getHttpStatusResult()->isError());
    }

    public function testCreateBookKeyWithNotFoundBookError(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 99999);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBook' => Expected::once(function (int $bookId) {
                return Book::create();
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'createKey' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $bookService->createBookKey($requestDTO);

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $responseDto);
        $this->assertEquals([], $responseDto->getKey());
        $this->assertEquals(new ResourceNotFoundError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    public function testCreateBookKeyWithNotAvailableBook(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 400);

        $bookMock = $this->make(Book::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
            'get' => Expected::once(function (string $bookGetKey) {
                return false;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBook' => Expected::once(function (int $bookId) use ($bookMock) {
                return $bookMock;
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'createKey' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $bookService->createBookKey($requestDTO);

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $responseDto);
        $this->assertEquals([], $responseDto->getKey());
        $this->assertEquals(new NoAccessToResourceError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    public function testCreateBookKeyWithNotPossibleToDownloadBook(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 400);

        $bookMock = $this->make(Book::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
            'get' => Expected::once(function (string $bookGetKey) {
                return true;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::once(function (Book $book) {
                return false;
            }),
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBook' => Expected::once(function (int $bookId) use ($bookMock) {
                return $bookMock;
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'createKey' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $bookService->createBookKey($requestDTO);

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $responseDto);
        $this->assertEquals([], $responseDto->getKey());
        $this->assertEquals(new NotAvailableForDownloadError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    // ->getBookMeta() tests

    public function testGetBookMetaWithExistAndPossibleToDownloadBook(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);
        $expectedMeta = 'metadata';

        $bookMock = $this->make(Book::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::once(function (Book $book) {
                return true;
            }),
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBook' => Expected::once(function (int $bookId) use ($bookMock) {
                return $bookMock;
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'getMeta' => Expected::once(function (Book $book) use ($expectedMeta) {
                return $expectedMeta;
            }),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::once(function (): void{
                // void
            }),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $bookService->getBookMeta($requestDTO);

        $this->assertInstanceOf(DocumentMetaResponseDTO::class, $responseDto);
        $this->assertEquals($expectedMeta, $responseDto->getMeta());
        $this->assertEquals(SuccessOk200::create(), $responseDto->getHttpStatusResult());
        $this->assertEquals(false, $responseDto->getHttpStatusResult()->isError());
    }

    public function testGetBookMetaWithNotFoundBookError(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBook' => Expected::once(function (int $bookId) {
                return Book::create();
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'getMeta' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $bookService->getBookMeta($requestDTO);

        $this->assertInstanceOf(DocumentMetaResponseDTO::class, $responseDto);
        $this->assertEquals('', $responseDto->getMeta());
        $this->assertEquals(new ResourceNotFoundError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    public function testGetBookMetaWithNotPossibleToDownloadBook(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);

        $bookMock = $this->make(Book::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
        ]);


        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::once(function (Book $book) {
                return false;
            }),
        ]);

        $bookRepositoryMock = $this->make(BookRepository::class, [
            'getBook' => Expected::once(function (int $bookId) use ($bookMock) {
                return $bookMock;
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'getMeta' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::once(function (): void{
                // void
            }),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $bookService = new BookService(
            bookRepository: $bookRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $bookService->getBookMeta($requestDTO);

        $this->assertInstanceOf(DocumentMetaResponseDTO::class, $responseDto);
        $this->assertEquals('', $responseDto->getMeta());
        $this->assertEquals(new NotAvailableForDownloadError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    // ->getDownloadFilePath() tests

    public function testGetDownloadFilePathWithPossibleToDownloadBook(): void
    {
        foreach ($this->fileTypesClassnames as $fileTypeClassname) {
            $requestDTO = new DocumentIdRequestDTO(id: 1);
            $expectedFilePath = '/book/1/pdf_path.pdf';

            $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
                'isDownloadPossible' => Expected::once(function (Book $book) {
                    return true;
                }),
            ]);

            $bookRepositoryMock = $this->make(BookRepository::class, [
                'getBook' => Expected::once(function (int $bookId) {
                    return Book::create();
                }),
            ]);

            $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
                'getEncryptedFilePath' => Expected::once(function (Book $book, FileTypeInterface $fileType) use ($expectedFilePath) {
                    return $expectedFilePath;
                }),
            ]);

            $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
                'logSubscriberVisit' =>  Expected::never(),
                'logDocumentRead' =>  Expected::once(function (): void{
                    // void
                }),
                'logDocumentFail' =>  Expected::never(),
            ]);

            $bookService = new BookService(
                bookRepository: $bookRepositoryMock,
                downloadProtectorService: $downloadProtectorServiceMock,
                documentCryptService: $documentCryptServiceMock,
                userActivityLogService: $userActivityLogServiceMock,
            );

            $fileType = new $fileTypeClassname();
            $responseDto = $bookService->getDownloadFilePath($requestDTO, $fileType);

            $this->assertInstanceOf(DocumentDownloadResponseDTO::class, $responseDto);
            $this->assertEquals($expectedFilePath, $responseDto->getFilePath());
            $this->assertEquals(SuccessOk200::create(), $responseDto->getHttpStatusResult());
            $this->assertEquals(false, $responseDto->getHttpStatusResult()->isError());
        }
    }

    public function testGetDownloadFilePathWithNotPossibleToDownloadBook(): void
    {
        foreach ($this->fileTypesClassnames as $fileTypeClassname) {
            $requestDTO = new DocumentIdRequestDTO(id: 99999);

            $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
                'isDownloadPossible' => Expected::once(function (Book $book) {
                    return false;
                }),
            ]);

            $bookRepositoryMock = $this->make(BookRepository::class, [
                'getBook' => Expected::once(function (int $bookId) {
                    return Book::create();
                }),
            ]);

            $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
                'getEncryptedFilePath' => Expected::never(),
            ]);

            $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
                'logSubscriberVisit' =>  Expected::never(),
                'logDocumentRead' =>  Expected::once(function (): void{
                    // void
                }),
                'logDocumentFail' =>  Expected::never(),
            ]);

            $bookService = new BookService(
                bookRepository: $bookRepositoryMock,
                downloadProtectorService: $downloadProtectorServiceMock,
                documentCryptService: $documentCryptServiceMock,
                userActivityLogService: $userActivityLogServiceMock,
            );

            $fileType = new $fileTypeClassname();
            $responseDto = $bookService->getDownloadFilePath($requestDTO, $fileType);

            $this->assertInstanceOf(DocumentDownloadResponseDTO::class, $responseDto);
            $this->assertEquals('', $responseDto->getFilePath());
            $this->assertEquals(new NotAvailableForDownloadError(), $responseDto->getHttpStatusResult());
            $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
        }
    }
}

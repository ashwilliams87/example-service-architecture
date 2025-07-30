<?php

namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ebs\Model\Journal_Article;
use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\DataTypes\FileTypes\AudioFileType;
use Lan\DataTypes\FileTypes\EpubFileType;
use Lan\DataTypes\FileTypes\PdfFileType;
use Lan\DataTypes\FileTypes\TextFileType;
use Lan\DataTypes\RequestResult\Error\NoAccessToResourceError;
use Lan\DataTypes\RequestResult\Error\NotAvailableForDownloadError;
use Lan\DataTypes\RequestResult\Error\ResourceNotFoundError;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Book\Responses\DocumentDownloadResponseDTO;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;
use Lan\DTOs\Document\DocumentCipherKeyResponseDTO;
use Lan\DTOs\Document\DocumentIdRequestDTO;
use Lan\DTOs\Document\DocumentMetaResponseDTO;
use Lan\DTOs\Journal\Responses\ArticleListResponseDTO;
use Lan\DTOs\Journal\Responses\JournalIssueList\JournalIssuesCardDTO;
use Lan\DTOs\Journal\Responses\JournalListResponseDTO;
use Lan\Repositories\JournalRepository;
use Lan\Services\JournalService;
use Lan\Services\Security\DocumentCryptService;
use Lan\Services\Security\DownloadProtectService;
use Lan\Services\UserActivityLogService;
use Tests\Support\UnitTester;

class JournalServiceTest extends Unit
{
    protected array $fileTypesClassnames = [
        PdfFileType::class,
        EpubFileType::class,
        TextFileType::class,
        AudioFileType::class,
    ];

    protected UnitTester $tester;

    // ->createArticleKey() tests

    public function testCreateArticleKeyWithAvailableAndPossibleToDownloadArticle(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);
        $expectedKeys = [264, 249, 327, 348, 330, 390, 411, 426, 336, 345, 390, 414];

        $articleMock = $this->make(Journal_Article::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
            'get' => Expected::once(function (string $articleGetKey) {
                return true;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::once(function (Journal_Article $article) {
                return true;
            }),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticle' => Expected::once(function (int $articleId) use ($articleMock) {
                return $articleMock;
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'createKey' => Expected::once(function (Journal_Article $article) {
                return [264, 249, 327, 348, 330, 390, 411, 426, 336, 345, 390, 414];
            }),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->createArticleKey($requestDTO);

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $responseDto);
        $this->assertEquals($expectedKeys, $responseDto->getKey());
        $this->assertEquals(SuccessOk200::create(), $responseDto->getHttpStatusResult());
        $this->assertEquals(false, $responseDto->getHttpStatusResult()->isError());
    }

    public function testCreateArticleKeyWithNotFoundArticleError(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 99999);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticle' => Expected::once(function (int $articleId) {
                return Journal_Article::create();
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

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->createArticleKey($requestDTO);

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $responseDto);
        $this->assertEquals([], $responseDto->getKey());
        $this->assertEquals(new ResourceNotFoundError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    public function testCreateArticleKeyWithNotAvailableArticle(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 400);

        $articleMock = $this->make(Journal_Article::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
            'get' => Expected::once(function (string $articleGetKey) {
                return false;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticle' => Expected::once(function (int $articleId) use ($articleMock) {
                return $articleMock;
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

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->createArticleKey($requestDTO);

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $responseDto);
        $this->assertEquals([], $responseDto->getKey());
        $this->assertEquals(new NoAccessToResourceError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    public function testCreateArticleKeyWithNotPossibleToDownloadArticle(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 400);

        $articleMock = $this->make(Journal_Article::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
            'get' => Expected::once(function (string $articleGetKey) {
                return true;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::once(function (Journal_Article $article) {
                return false;
            }),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticle' => Expected::once(function (int $articleId) use ($articleMock) {
                return $articleMock;
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

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->createArticleKey($requestDTO);

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $responseDto);
        $this->assertEquals([], $responseDto->getKey());
        $this->assertEquals(new NotAvailableForDownloadError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    // ->getArticleMeta() tests

    public function testGetArticleMetaWithExistAndPossibleToDownloadArticle(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);
        $expectedMeta = 'metadata';

        $articleMock = $this->make(Journal_Article::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::once(function (Journal_Article $article) {
                return true;
            }),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticle' => Expected::once(function (int $articleId) use ($articleMock) {
                return $articleMock;
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'getMeta' => Expected::once(function (Journal_Article $article) use ($expectedMeta) {
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

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->getArticleMeta($requestDTO);

        $this->assertInstanceOf(DocumentMetaResponseDTO::class, $responseDto);
        $this->assertEquals($expectedMeta, $responseDto->getMeta());
        $this->assertEquals(SuccessOk200::create(), $responseDto->getHttpStatusResult());
        $this->assertEquals(false, $responseDto->getHttpStatusResult()->isError());
    }

    public function testGetArticleMetaWithNotFoundArticleError(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticle' => Expected::once(function (int $articleId) {
                return Journal_Article::create();
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

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->getArticleMeta($requestDTO);

        $this->assertInstanceOf(DocumentMetaResponseDTO::class, $responseDto);
        $this->assertEquals('', $responseDto->getMeta());
        $this->assertEquals(new ResourceNotFoundError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    public function testGetArticleMetaWithNotPossibleToDownloadArticle(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 1);

        $articleMock = $this->make(Journal_Article::class, [
            'getPk' => Expected::once(function () {
                return 1;
            }),
        ]);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::once(function (Journal_Article $article) {
                return false;
            }),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticle' => Expected::once(function (int $articleId) use ($articleMock) {
                return $articleMock;
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

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->getArticleMeta($requestDTO);

        $this->assertInstanceOf(DocumentMetaResponseDTO::class, $responseDto);
        $this->assertEquals('', $responseDto->getMeta());
        $this->assertEquals(new NotAvailableForDownloadError(), $responseDto->getHttpStatusResult());
        $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
    }

    // ->getArticleDownloadFilePath() tests

    public function testGetArticleDownloadFilePathWithPossibleToDownloadArticle(): void
    {
        foreach ($this->fileTypesClassnames as $fileTypeClassname) {
            $requestDTO = new DocumentIdRequestDTO(id: 1);
            $expectedFilePath = '/Article/1/pdf_path.pdf';

            $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
                'isDownloadPossible' => Expected::once(function (Journal_Article $article) {
                    return true;
                }),
            ]);

            $journalRepositoryMock = $this->make(JournalRepository::class, [
                'getArticle' => Expected::once(function (int $articleId) {
                    return Journal_Article::create();
                }),
            ]);

            $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
                'getEncryptedFilePath' => Expected::once(function (Journal_Article $article, FileTypeInterface $fileType) use ($expectedFilePath) {
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

            $journalService = new JournalService(
                journalRepository: $journalRepositoryMock,
                downloadProtectorService: $downloadProtectorServiceMock,
                documentCryptService: $documentCryptServiceMock,
                userActivityLogService: $userActivityLogServiceMock,
            );

            $fileType = new $fileTypeClassname();
            $responseDto = $journalService->getArticleDownloadFilePath($requestDTO, $fileType);

            $this->assertInstanceOf(DocumentDownloadResponseDTO::class, $responseDto);
            $this->assertEquals($expectedFilePath, $responseDto->getFilePath());
            $this->assertEquals(SuccessOk200::create(), $responseDto->getHttpStatusResult());
            $this->assertEquals(false, $responseDto->getHttpStatusResult()->isError());
        }
    }

    public function testGetArticleDownloadFilePathWithNotPossibleToDownloadArticle(): void
    {
        foreach ($this->fileTypesClassnames as $fileTypeClassname) {
            $requestDTO = new DocumentIdRequestDTO(id: 99999);

            $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
                'isDownloadPossible' => Expected::once(function (Journal_Article $article) {
                    return false;
                }),
            ]);

            $journalRepositoryMock = $this->make(JournalRepository::class, [
                'getArticle' => Expected::once(function (int $articleId) {
                    return Journal_Article::create();
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

            $journalService = new JournalService(
                journalRepository: $journalRepositoryMock,
                downloadProtectorService: $downloadProtectorServiceMock,
                documentCryptService: $documentCryptServiceMock,
                userActivityLogService: $userActivityLogServiceMock,
            );

            $fileType = new $fileTypeClassname();
            $responseDto = $journalService->getArticleDownloadFilePath($requestDTO, $fileType);

            $this->assertInstanceOf(DocumentDownloadResponseDTO::class, $responseDto);
            $this->assertEquals('', $responseDto->getFilePath());

            $this->assertEquals(get_class(new NotAvailableForDownloadError()), get_class($responseDto->getHttpStatusResult()));
            $this->assertEquals(true, $responseDto->getHttpStatusResult()->isError());
        }
    }

    public function testGetArticlesFromJournal(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 292672);


        $expectedJournalList = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedJournalList[] = [
                "all_count" => 1,
                "available" => 1,
                "journal_pk" => 292672,
                "journal_name" => "Journal name",
                "access_date" => "2025-06-03 12:20:53",
                "pages" => "5-" . $i,
                "journalId" => 292672,
                "journalName" => "Journal name",
                "journal_issue_pk" => 292672,
                "issue" => "1",
                "year" => 2014,
                "publisher" => "Московский технологический институт",
                "id" => $i,
                "title" => "Test Journal Title " . $i,
                "author" => "Author " . $i,
                "start_page" => 5,
                "finish_page" => 16,
                "description" => "Test description " . $i,
                "expired_date" => "2025-06-05",
            ];
        }

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticlesFromJournal' => Expected::once(function (DocumentIdRequestDTO $requestDTO) use ($expectedJournalList) {
                return $expectedJournalList;
            }),
        ]);

        $expectedServiceResult = ArticleListResponseDTO::createFromArrayList($expectedJournalList);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'getEncryptedFilePath' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $result = $journalService->getArticlesFromJournal($requestDTO);

        $this->assertInstanceOf(ArticleListResponseDTO::class, $result);
        $this->assertEquals($expectedServiceResult, $result);
    }

    public function testGetJournalsFromCatalog(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            categoryId: 917,
            subCategoryId: 0,
            publisherId: 0,
            limit: 20,
            offset: 0,
        );


        $expectedRepositoryResponse = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedRepositoryResponse['journals'][] = [
                "all_count" => 1,
                "available" => 1,
                "active" => 1,
                "cover" => "http://test-cover/" . $i,
                "id" => $i,
                "title" => "Test Journal Title " . $i,
                "publisher" => "Test Publisher " . $i,
            ];
        }
        $expectedRepositoryResponse['count'] = 50;

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getJournalsWithCoverLinksFromCatalog' => Expected::once(function (DocumentsTypeRequestDTO $requestDTO) use ($expectedRepositoryResponse) {
                return $expectedRepositoryResponse;
            }),
            'addCoverLinksToJournalRows' => Expected::never(),
        ]);

        $expectedServiceResult = JournalListResponseDTO::createFromArrayList($expectedRepositoryResponse['journals'])
            ->setCount($expectedRepositoryResponse['count']);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'getEncryptedFilePath' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $result = $journalService->getJournalsFromCatalog($requestDTO);

        $this->assertInstanceOf(JournalListResponseDTO::class, $result);
        $this->assertEquals($expectedServiceResult, $result);
    }

    public function testGetJournalIssuesOnExistJournal(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 123);
        $repositoryReturnedRows = [
            "id" => 2373,
            "title" => "Cloud of science",
            "issueperyear" => "4",
            "issn" => "2409-031X",
            "vac" => 0,
            "edition" => "<ul><li>Главный редактор - Никульчев Е. В., д. т. н., профессор проректор, Московский технологический институт (Россия, Москва)</li></ul>",
            "description" => "Журнал содержит результаты прикладных и фундаментальных научных исследований в области информационных технологий, моделирования систем, прикладных информационных технологий и других областей науки.",
            "email" => null,
            "publisher" => "Московский технологический институт",
            "city" => "Москва",
            "cover" => 'https://' . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', '/coverpath/'),
            "country" => "Россия",
            "year" => 2013,
            "publish_year" => 2014,
            "available" => 1,
            "active" => 1,
            "years" => [
                [
                    "name" => '2013',
                    "issues" => [
                        [
                            "title" => "1",
                            "id" => 292672
                        ],
                        [
                            "title" => "2",
                            "id" => 292674
                        ],
                        [
                            "title" => "3",
                            "id" => 292671
                        ],
                        [
                            "title" => "4",
                            "id" => 292673
                        ]
                    ]
                ],
                [
                    "name" => '2014',
                    "issues" => [
                        [
                            "title" => "3",
                            "id" => 292674
                        ],
                        [
                            "title" => "4",
                            "id" => 292675
                        ],
                        [
                            "title" => "5",
                            "id" => 292676
                        ],
                        [
                            "title" => "6",
                            "id" => 292677
                        ]
                    ]
                ]
            ]
        ];

        $expectedDto = JournalIssuesCardDTO::createFromArray($repositoryReturnedRows);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getJournalWithIssuesWithCover' => Expected::once(function (int $journalId) use ($repositoryReturnedRows) {
                return $repositoryReturnedRows;
            }),
            'getArticle' => Expected::never(),
            'getJournalIssueCoverId' => Expected::never(),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'getEncryptedFilePath' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->getJournalIssuesCard($requestDTO);

        $this->assertInstanceOf(JournalIssuesCardDTO::class, $responseDto);
        $this->assertEquals($expectedDto, $responseDto);
        $this->assertFalse($responseDto->isEmpty());
    }

    public function testGetJournalIssuesOnNonExistJournal(): void
    {
        $requestDTO = new DocumentIdRequestDTO(id: 999999999);

        $downloadProtectorServiceMock = $this->make(DownloadProtectService::class, [
            'isDownloadPossible' => Expected::never(),
        ]);

        $journalRepositoryMock = $this->make(JournalRepository::class, [
            'getArticle' => Expected::never(),
            'getJournalIssueCoverId' => Expected::never(),
            'getJournalWithIssues' => Expected::once(function (int $journalId) {
                return [];
            }),
        ]);

        $documentCryptServiceMock = $this->make(DocumentCryptService::class, [
            'getEncryptedFilePath' => Expected::never(),
        ]);

        $userActivityLogServiceMock = $this->make(UserActivityLogService::class, [
            'logSubscriberVisit' =>  Expected::never(),
            'logDocumentRead' =>  Expected::never(),
            'logDocumentFail' =>  Expected::never(),
        ]);

        $journalService = new JournalService(
            journalRepository: $journalRepositoryMock,
            downloadProtectorService: $downloadProtectorServiceMock,
            documentCryptService: $documentCryptServiceMock,
            userActivityLogService: $userActivityLogServiceMock,
        );

        $responseDto = $journalService->getJournalIssuesCard($requestDTO);

        $this->assertInstanceOf(JournalIssuesCardDTO::class, $responseDto);
        $this->assertEquals(JournalIssuesCardDTO::create(), $responseDto);
        $this->assertTrue($responseDto->isEmpty());
    }
}

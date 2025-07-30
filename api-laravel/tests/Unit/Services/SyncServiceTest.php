<?php

namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Lan\DTOs\Sync\Requests\DocumentsAndBookmarksAtTimeRequestDTO;
use Lan\DTOs\Sync\Responses\BookmarksAndFavoritesResponseDTO;
use Lan\Repositories\SyncRepository;
use Lan\Services\SyncService;
use Tests\Support\UnitTester;

class SyncServiceTest extends Unit
{
    protected UnitTester $tester;

    public function testSyncAllWithFullPayload(): void
    {
        $requestDTO = new DocumentsAndBookmarksAtTimeRequestDTO(
            bookmarks: [
                [
                    'article' => 0,
                    'entity_id' => '195479',
                    'cfi' => null,
                    'updated_at' => 1717571500,
                    'entity' => 'book',
                    'id' => 4,
                    'marker' => 3,
                    'owner' => 'test@test.ru',
                    'page' => '3',
                ],
            ],
            books: [
                [
                    'entity_id' => 124603,
                    'sync_active' => 1,
                    'updated_at' => 1717523304,
                ],
                [
                    'entity_id' => 192702,
                    'sync_active' => 1,
                    'updated_at' => 1717523304,
                ],
            ],
            journalArticles: [
                [
                    'entity_id' => 90879,
                    'sync_active' => 1,
                    'updated_at' => 1717523304,
                ],
                [
                    'entity_id' => 306720,
                    'sync_active' => 1,
                    'updated_at' => 1717523304,
                ],
            ],
            deviceTime: 1717571500,
        );

        $expectedServerFavorite = [
            [
                'article' => 0,
                'entity_id' => '195479',
                'cfi' => null,
                'updated_at' => 1717571500,
                'entity' => 'book',
                'id' => 4,
                'marker' => 3,
                'owner' => 'test@test.ru',
                'page' => '3',
                'sync_active' => 1,
                'type' => 'pdf',
            ],
        ];

        $expectedServerBookMarks = [
            [
                'access_date' => '2025-06-03',
                'active' => true,
                'issue' => null,
                'journal' => null,
                'title' => 'Автоматизация технологических процессов и производств',
                'author' => null,
                'publisher' => 'Издательство "Инфра-Инженерия"',
                'id' => 124603,
                'entity' => 'book',
                'updated_at' => 1717523303,
                'sync_active' => 1,
                'cover' => 'https://ebs.landev.ru/img/cover/book/124603.jpg',
            ],
        ];

        $syncRepositoryMock = $this->make(SyncRepository::class, [
            'setTimeDiff' => Expected::once(function (int $serverTimeStamp, int $deviceTimeStamp): void {
                return;
            }),
            'updateUserLastFavorite' => Expected::once(),
            'synchronizeBookMarks' => Expected::once(function (DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void {
                return;
            }),
            'synchronizeFavoriteBooks' => Expected::once(function (DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void {
                return;
            }),
            'synchronizeFavoriteJournalArticles' => Expected::once(function (DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void {
                return;
            }),
            'getServerBookMarks' => Expected::once(function () use ($expectedServerBookMarks) {
                return $expectedServerBookMarks;
            }),
            'getServerFavoriteData' => Expected::once(function () use ($expectedServerFavorite){
                return $expectedServerFavorite;
            }),
        ]);

        $bookService = new SyncService(
            syncRepository: $syncRepositoryMock,
        );

        $responseDto = $bookService->syncBookmarkAndFavorites($requestDTO);

        $this->assertInstanceOf(BookmarksAndFavoritesResponseDTO::class, $responseDto);
        $this->assertEquals($expectedServerBookMarks, $responseDto->getBookmarks());
        $this->assertEquals($expectedServerFavorite, $responseDto->getFavorites());
    }

    public function testSyncAllWithOnlyBookMarksPayload(): void
    {
        $requestDTO = new DocumentsAndBookmarksAtTimeRequestDTO(
            bookmarks: [
                [
                    'article' => 0,
                    'entity_id' => '195479',
                    'cfi' => null,
                    'updated_at' => 1717571500,
                    'entity' => 'book',
                    'id' => 4,
                    'marker' => 3,
                    'owner' => 'test@test.ru',
                    'page' => '3',
                ],
            ],
            books: [],
            journalArticles: [],
            deviceTime: 1717571500,
        );

        $expectedServerBookMarks = [
            [
                'access_date' => '2025-06-03',
                'active' => true,
                'issue' => null,
                'journal' => null,
                'title' => 'Автоматизация технологических процессов и производств',
                'author' => null,
                'publisher' => 'Издательство "Инфра-Инженерия"',
                'id' => 124603,
                'entity' => 'book',
                'updated_at' => 1717523303,
                'sync_active' => 1,
                'cover' => 'https://ebs.landev.ru/img/cover/book/124603.jpg',
            ],
        ];

        $syncRepositoryMock = $this->make(SyncRepository::class, [
            'setTimeDiff' => Expected::once(function (int $serverTimeStamp, int $deviceTimeStamp): void {
                return;
            }),
            'updateUserLastFavorite' => Expected::once(),
            'synchronizeBookMarks' => Expected::once(function (DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void {
                return;
            }),
            'synchronizeFavoriteBooks' => Expected::never(),
            'synchronizeFavoriteJournalArticles' => Expected::never(),
            'getServerBookMarks' => Expected::once(function () use ($expectedServerBookMarks) {
                return $expectedServerBookMarks;
            }),
            'getServerFavoriteData' => Expected::once(function () {
                return [];
            }),
        ]);

        $bookService = new SyncService(
            syncRepository: $syncRepositoryMock,
        );

        $responseDto = $bookService->syncBookmarkAndFavorites($requestDTO);

        $this->assertInstanceOf(BookmarksAndFavoritesResponseDTO::class, $responseDto);
        $this->assertEquals($expectedServerBookMarks, $responseDto->getBookmarks());
        $this->assertEquals([], $responseDto->getFavorites());
    }

    public function testSyncAllWithOnlyBooksPayload(): void
    {
        $requestDTO = new DocumentsAndBookmarksAtTimeRequestDTO(
            bookmarks: [],
            books: [
                [
                    'entity_id' => 124603,
                    'sync_active' => 1,
                    'updated_at' => 1717523304,
                ],
                [
                    'entity_id' => 192702,
                    'sync_active' => 1,
                    'updated_at' => 1717523304,
                ],
            ],
            journalArticles: [],
            deviceTime: 1717571500,
        );


        $syncRepositoryMock = $this->make(SyncRepository::class, [
            'setTimeDiff' => Expected::once(function (int $serverTimeStamp, int $deviceTimeStamp): void {
                return;
            }),
            'updateUserLastFavorite' => Expected::once(),
            'synchronizeBookMarks' => Expected::never(),
            'synchronizeFavoriteBooks' => Expected::once(function (DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void {
                return;
            }),
            'synchronizeFavoriteJournalArticles' => Expected::never(),
            'getServerBookMarks' => Expected::once(function () {
                return [];
            }),
            'getServerFavoriteData' => Expected::once(function () {
                return [];
            }),
        ]);

        $bookService = new SyncService(
            syncRepository: $syncRepositoryMock,
        );

        $responseDto = $bookService->syncBookmarkAndFavorites($requestDTO);

        $this->assertInstanceOf(BookmarksAndFavoritesResponseDTO::class, $responseDto);
        $this->assertEquals([], $responseDto->getBookmarks());
        $this->assertEquals([], $responseDto->getFavorites());
    }

    public function testSyncAllWithOnlyJournalArticlesPayload(): void
    {
        $requestDTO = new DocumentsAndBookmarksAtTimeRequestDTO(
            bookmarks: [],
            books: [],
            journalArticles: [
                [
                    'entity_id' => 90879,
                    'sync_active' => 1,
                    'updated_at' => 1717523304,
                ],
                [
                    'entity_id' => 306720,
                    'sync_active' => 1,
                    'updated_at' => 1717523304,
                ],
            ],
            deviceTime: 1717571500,
        );

         $expectedServerFavorite = [
            [
                'article' => 0,
                'entity_id' => '195479',
                'cfi' => null,
                'updated_at' => 1717571500,
                'entity' => 'book',
                'id' => 4,
                'marker' => 3,
                'owner' => 'test@test.ru',
                'page' => '3',
                'sync_active' => 1,
                'type' => 'pdf',
            ],
        ];

        $syncRepositoryMock = $this->make(SyncRepository::class, [
            'setTimeDiff' => Expected::once(function (int $serverTimeStamp, int $deviceTimeStamp): void {
                return;
            }),
            'updateUserLastFavorite' => Expected::once(),
            'synchronizeBookMarks' => Expected::never(),
            'synchronizeFavoriteBooks' => Expected::never(),
            'synchronizeFavoriteJournalArticles' => Expected::once(function (DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void {
                return;
            }),
            'getServerBookMarks' => Expected::once(function () {
                return [];
            }),
            'getServerFavoriteData' => Expected::once(function () use ($expectedServerFavorite){
                return $expectedServerFavorite;
            }),
        ]);

        $bookService = new SyncService(
            syncRepository: $syncRepositoryMock,
        );

        $responseDto = $bookService->syncBookmarkAndFavorites($requestDTO);

        $this->assertInstanceOf(BookmarksAndFavoritesResponseDTO::class, $responseDto);
        $this->assertEquals([], $responseDto->getBookmarks());
        $this->assertEquals($expectedServerFavorite, $responseDto->getFavorites());
    }
}

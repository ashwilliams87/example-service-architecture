<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DTOs\Sync\Requests\DocumentsAndBookmarksAtTimeRequestDTO;

class SyncAllRequestDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $bookmarks = [];
        $books = [];
        $journalArticles = [];

        $dto = new DocumentsAndBookmarksAtTimeRequestDTO(
            bookmarks: $bookmarks,
            books: $books,
            journalArticles: $journalArticles,
            deviceTime: 12321234
        );

        $this->assertInstanceOf(DocumentsAndBookmarksAtTimeRequestDTO::class, $dto);
        $this->assertEquals($bookmarks, $dto->getBookmarks());
        $this->assertEquals($books, $dto->getBooks());
        $this->assertEquals($journalArticles, $dto->getJournalArticles());
        $this->assertEquals(12321234, $dto->getDeviceTime());
    }
}

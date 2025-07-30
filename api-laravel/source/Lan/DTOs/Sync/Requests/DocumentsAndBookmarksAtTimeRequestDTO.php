<?php

namespace Lan\DTOs\Sync\Requests;


use Lan\Contracts\DTOs\Sync\DocumentsAndBookmarksAtTimeDTOInterface;

readonly class DocumentsAndBookmarksAtTimeRequestDTO implements DocumentsAndBookmarksAtTimeDTOInterface
{
    public function __construct(
        private array $bookmarks,
        private array $books,
        private array $journalArticles,
        private int   $deviceTime,
    )
    {

    }

    public function getBookmarks(): array
    {
        return $this->bookmarks;
    }

    public function getBooks(): array
    {
        return $this->books;
    }

    public function getJournalArticles(): array
    {
        return $this->journalArticles;
    }

    public function getDeviceTime(): int
    {
        return $this->deviceTime;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}

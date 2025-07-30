<?php
namespace Lan\Contracts\DTOs\Sync;

use Lan\Contracts\DTOs\LanDTOInterface;

interface DocumentsAndBookmarksAtTimeDTOInterface extends LanDTOInterface
{
    public function getBookmarks(): array;

    public function getBooks(): array;

    public function getJournalArticles(): array;

    public function getDeviceTime(): int;
}

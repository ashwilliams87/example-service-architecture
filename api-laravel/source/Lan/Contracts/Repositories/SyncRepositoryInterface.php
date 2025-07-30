<?php

namespace Lan\Contracts\Repositories;

use Lan\DTOs\Sync\Requests\DocumentsAndBookmarksAtTimeRequestDTO;

interface SyncRepositoryInterface
{
    public function updateUserLastFavorite(): void;

    public function getServerBookMarks(): array;

    public function getServerFavoriteData(): array;

    public function synchronizeBookMarks(DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void;

    public function synchronizeFavoriteBooks(DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void;

    public function synchronizeFavoriteJournalArticles(DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void;
}

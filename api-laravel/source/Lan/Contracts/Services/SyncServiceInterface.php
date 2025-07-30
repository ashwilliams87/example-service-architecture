<?php

namespace Lan\Contracts\Services;

use Lan\Contracts\DTOs\Sync\BookmarksAndFavoritesDTOInterface;
use Lan\Contracts\DTOs\Sync\DocumentsAndBookmarksAtTimeDTOInterface;

interface SyncServiceInterface
{
    public function syncBookmarkAndFavorites(DocumentsAndBookmarksAtTimeDTOInterface $documentsAndBookmarksAtTimeDTO, int $serverTimeStamp = 0): BookmarksAndFavoritesDTOInterface;
}

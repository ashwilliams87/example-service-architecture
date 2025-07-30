<?php

namespace Lan\Services;

use Lan\Contracts\DTOs\Sync\DocumentsAndBookmarksAtTimeDTOInterface;
use Lan\Contracts\Repositories\SyncRepositoryInterface;
use Lan\Contracts\Services\SyncServiceInterface;
use Lan\DTOs\Sync\Responses\BookmarksAndFavoritesResponseDTO;

class SyncService implements SyncServiceInterface
{
    public function __construct(
        private SyncRepositoryInterface $syncRepository,
    )
    {

    }

    public function syncBookmarkAndFavorites(
        DocumentsAndBookmarksAtTimeDTOInterface $documentsAndBookmarksAtTimeDTO,
        int $serverTimeStamp = 0
    ): BookmarksAndFavoritesResponseDTO
    {
        if ($serverTimeStamp === 0) {
            $serverTimeStamp = time() + 3600 * 3;
        }

        $deviceTimeStamp = $documentsAndBookmarksAtTimeDTO->getDeviceTime();
        if ($deviceTimeStamp === 0) {
            $deviceTimeStamp = time();
        }

        $this->syncRepository->setTimeDiff($serverTimeStamp, $deviceTimeStamp);

        $this->syncRepository->updateUserLastFavorite();

        if (!empty($documentsAndBookmarksAtTimeDTO->getBookmarks())) {
            $this->syncRepository->synchronizeBookMarks($documentsAndBookmarksAtTimeDTO);
        }

        if (!empty($documentsAndBookmarksAtTimeDTO->getBooks())) {
            $this->syncRepository->synchronizeFavoriteBooks($documentsAndBookmarksAtTimeDTO);
        }

        if (!empty($documentsAndBookmarksAtTimeDTO->getJournalArticles())) {
            $this->syncRepository->synchronizeFavoriteJournalArticles($documentsAndBookmarksAtTimeDTO);
        }

        return BookmarksAndFavoritesResponseDTO::createFromBookmarksAndFavorites(
            bookmarks: $this->syncRepository->getServerBookMarks(),
            favorites: $this->syncRepository->getServerFavoriteData()
        );
    }
}

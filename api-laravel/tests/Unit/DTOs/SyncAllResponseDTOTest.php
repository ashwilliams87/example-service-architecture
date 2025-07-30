<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DTOs\Sync\Responses\BookmarksAndFavoritesResponseDTO;

class SyncAllResponseDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $bookMarks = [];
        $favorite = [];

        $dto = BookmarksAndFavoritesResponseDTO::createFromBookmarksAndFavorites(
            bookmarks: $bookMarks,
            favorites: $favorite,
        );

        $this->assertInstanceOf(BookmarksAndFavoritesResponseDTO::class, $dto);
        $this->assertEquals($bookMarks, $dto->getBookmarks());
        $this->assertEquals($favorite, $dto->getFavorites());
    }
}

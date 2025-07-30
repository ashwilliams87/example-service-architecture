<?php
namespace Lan\DTOs\Sync\Responses;

use Lan\Contracts\DTOs\Sync\BookmarksAndFavoritesDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Sync\BookmarksAndFavoritesTransformer;

class BookmarksAndFavoritesResponseDTO implements BookmarksAndFavoritesDTOInterface
{
    private function __construct(
        private readonly array $bookmarks,
        private readonly array $favorites,
    )
    {

    }

    public static function createFromBookmarksAndFavorites(
        array $bookmarks = [],
        array $favorites = [],
    ): static
    {
        return new self(
            bookmarks: $bookmarks,
            favorites: $favorites,
        );
    }

    public function getBookmarks(): array
    {
        return $this->bookmarks;
    }

    public function getFavorites(): array
    {
        return $this->favorites;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public function toMobileScheme(TransformMobile $transformer = new BookmarksAndFavoritesTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}

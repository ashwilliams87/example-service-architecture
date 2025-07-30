<?php

namespace Lan\Transformers\Sync;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Sync\BookmarksAndFavoritesDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class BookmarksAndFavoritesTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(BookmarksAndFavoritesDTOInterface $dto): array
    {

        return [
            'bookMarks' => $dto->getBookmarks(),
            'favorite' => $dto->getFavorites(),
        ];
    }
}

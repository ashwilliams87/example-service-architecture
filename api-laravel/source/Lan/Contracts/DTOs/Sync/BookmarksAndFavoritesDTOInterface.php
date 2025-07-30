<?php

namespace Lan\Contracts\DTOs\Sync;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;

interface BookmarksAndFavoritesDTOInterface extends LanDTOInterface, Mobile
{
    public function getBookmarks(): array;

    public function getFavorites(): array;
}

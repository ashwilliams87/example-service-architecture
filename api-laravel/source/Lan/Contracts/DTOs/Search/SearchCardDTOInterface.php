<?php

namespace Lan\Contracts\DTOs\Search;

use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;

interface SearchCardDTOInterface extends LanDTOInterface, CreatableFromIceQueryResultRow, Mobile
{
    public function getId(): int;

    public function getType(): string;

    public function getTitle(): string;

    public function getCount(): int;

}

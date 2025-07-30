<?php

namespace Lan\Contracts\DTOs\Collection;

use Lan\Contracts\DTOs\Mobile;

interface LanDTOListInterface
{

    public function getAll(): array;

    public function length(): int;

    public static function getItemClass();

    public function itemToMobileScheme(Mobile $itemDTO): array;
}

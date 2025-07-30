<?php
namespace Lan\Contracts\DTOs\Category;

use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\LanDTOInterface;

interface CategoryDTOInterface extends LanDTOInterface, CreatableFromIceQueryResultRow
{
    public function getId(): int;

    public function getTitle(): string;

    public function isActive(): bool;
}

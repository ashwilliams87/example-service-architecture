<?php

namespace Lan\DTOs\Collection;

use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Collection\LanDTOListInterface;
use Lan\Contracts\DTOs\Mobile;

abstract class ListDTO implements LanDTOListInterface
{
    private $collection;
    private int $length;

    abstract static function getItemClass();

    protected function __construct(...$dtoCollection)
    {
        foreach ($dtoCollection as $dto) {
            if (!$dto instanceof (static::getItemClass())) {
                throw new \InvalidArgumentException('Invalid item class');
            }
        }
        $this->collection = $dtoCollection;
        $this->length = count($dtoCollection);
    }

    public function isValid(): bool
    {
        foreach ($this->collection as $item) {
            if (!$item->isValid()) {
                return false;
            }

            if (!$item instanceof (static::getItemClass())) {
                return false;
            }
        }
        return true;
    }

    public function getAll(): array
    {
        return $this->collection;
    }

    public function length(): int
    {
        return $this->length;
    }

    public static function createFromIceQueryResultRows(QueryResult $queryBuilder): static
    {
        $itemDTOs = [];
        $itemClass = static::getItemClass();

        foreach ($queryBuilder->getRows() as $row) {
            $itemDTOs[] = $itemClass::createFromIceQueryResultRow($row);
        }

        return new static(...$itemDTOs);
    }

    public static function createFromRows(array $itemRows): static
    {
        return new static(...$itemRows);
    }

    public function itemToMobileScheme(Mobile $itemDTO): array
    {
        return $itemDTO->toMobileScheme();
    }
}

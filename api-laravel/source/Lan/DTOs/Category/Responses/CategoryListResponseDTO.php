<?php

namespace Lan\DTOs\Category\Responses;

use Lan\Contracts\DTOs\Category\CategoryListResponseDTOInterface;
use Lan\Contracts\DTOs\CreatableFromIceQueryResultRowList;
use Lan\Contracts\DTOs\CreatableFromObjectList;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Collection\ListDTO;
use Lan\Transformers\Category\CategoryListTransformer;

class CategoryListResponseDTO extends ListDTO implements CategoryListResponseDTOInterface, CreatableFromIceQueryResultRowList, CreatableFromObjectList
{
    #[\Override]
    public static function getItemClass()
    {
        return CategoryDTO::class;
    }

    public static function createFromObjectList(array $objectList): static
    {
        $itemDTOs = [];
        $itemClass = static::getItemClass();

        foreach ($objectList as $object) {
            $itemDTOs[] = $itemClass::createFromObject($object);
        }

        return new static(...$itemDTOs);
    }

    public static function createFromIceQueryResultRowList(array $rows): static
    {
        $itemDTOs = [];
        $itemClass = static::getItemClass();

        foreach ($rows as $row) {
            $itemDTOs[] = $itemClass::createFromIceQueryResultRow($row);
        }

        return new static(...$itemDTOs);
    }

    public function toMobileScheme(TransformMobile $transformer = new CategoryListTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}

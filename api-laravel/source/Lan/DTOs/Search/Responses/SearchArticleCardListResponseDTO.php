<?php

namespace Lan\DTOs\Search\Responses;

use Lan\Contracts\DTOs\CreatableFromArrayList;
use Lan\Contracts\DTOs\MobileListDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Collection\ListDTO;
use Lan\Transformers\Search\SearchArticleCardListTransformer;

class SearchArticleCardListResponseDTO extends ListDTO implements MobileListDTOInterface, CreatableFromArrayList
{
    public function toMobileScheme(TransformMobile $transformer = new SearchArticleCardListTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    protected function __construct(SearchArticleCardDTO...$dtoCollection)
    {
        parent::__construct(...$dtoCollection);
    }

    #[\Override]
    public static function getItemClass()
    {
        return SearchArticleCardDTO::class;
    }

    public static function createFromArrayList(array $list): static
    {
        $itemDTOs = [];
        $itemClass = static::getItemClass();

        foreach ($list as $item) {
            $itemDTOs[] = $itemClass::createFromIceQueryResultRow($item);
        }

        return new static(...$itemDTOs);
    }
}

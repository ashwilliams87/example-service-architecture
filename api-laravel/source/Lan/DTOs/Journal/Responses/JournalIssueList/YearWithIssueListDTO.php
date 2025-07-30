<?php

namespace Lan\DTOs\Journal\Responses\JournalIssueList;

use Lan\Contracts\DTOs\CreatableFromArrayList;
use Lan\Contracts\DTOs\MobileListDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Collection\ListDTO;
use Lan\Transformers\Journal\YearWithIssueListTransformer;

class YearWithIssueListDTO extends ListDTO implements MobileListDTOInterface, CreatableFromArrayList
{
    protected function __construct(YearWithIssuesDTO...$dtoCollection)
    {
        parent::__construct(...$dtoCollection);
    }

    public function toMobileScheme(TransformMobile $transformer = new YearWithIssueListTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    #[\Override]
    public static function getItemClass()
    {
        return YearWithIssuesDTO::class;
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

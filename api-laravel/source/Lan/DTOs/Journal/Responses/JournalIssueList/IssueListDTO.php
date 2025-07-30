<?php

namespace Lan\DTOs\Journal\Responses\JournalIssueList;

use Lan\Contracts\DTOs\CreatableFromArrayList;
use Lan\Contracts\DTOs\MobileListDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Collection\ListDTO;
use Lan\Transformers\Journal\IssueListTransformer;

class IssueListDTO extends ListDTO implements MobileListDTOInterface, CreatableFromArrayList
{
    protected function __construct(IssueDTO...$dtoCollection)
    {
        parent::__construct(...$dtoCollection);
    }

    public function toMobileScheme(TransformMobile $transformer = new IssueListTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    #[\Override]
    public static function getItemClass()
    {
        return IssueDTO::class;
    }

    public static function createFromArrayList(array $list): static
    {
        $itemDTOs = [];
        $itemClass = static::getItemClass();

        foreach ($list as $item) {
            $itemDTOs[] = $itemClass::createFromArray($item);
        }

        return new static(...$itemDTOs);
    }
}

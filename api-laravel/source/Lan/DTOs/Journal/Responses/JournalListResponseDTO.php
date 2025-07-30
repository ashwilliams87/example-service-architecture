<?php

namespace Lan\DTOs\Journal\Responses;

use Lan\Contracts\DTOs\Countable;
use Lan\Contracts\DTOs\CreatableFromArrayList;
use Lan\Contracts\DTOs\MobileListDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Collection\ListDTO;
use Lan\Transformers\Journal\JournalListTransformer;

class JournalListResponseDTO extends ListDTO implements MobileListDTOInterface, CreatableFromArrayList, Countable
{
    private int $count;
    protected function __construct(JournalCardDTO...$dtoCollection)
    {
        parent::__construct(...$dtoCollection);
    }

    public function toMobileScheme(TransformMobile $transformer = new JournalListTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    #[\Override]
    public static function getItemClass()
    {
        return JournalCardDTO::class;
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

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;
        return $this;
    }
}

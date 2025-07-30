<?php

namespace Lan\DTOs\Book\Responses;

use Lan\Contracts\DTOs\Book\BookCardListResponseDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Collection\ListDTO;
use Lan\Transformers\Book\BookCardListTransformer;

class BookCardListResponseDTO extends ListDTO implements BookCardListResponseDTOInterface
{
    public function toMobileScheme(TransformMobile $transformer = new BookCardListTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    protected function __construct(BookCardDTO...$dtoCollection)
    {
        parent::__construct(...$dtoCollection);
    }

    #[\Override]
    public static function getItemClass()
    {
        return BookCardDTO::class;
    }
}

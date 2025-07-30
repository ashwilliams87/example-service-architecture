<?php

namespace Lan\Transformers\Book;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Book\Responses\BookCardListResponseDTO;

class BookCardListTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToMobileResponseArray($dto);
    }

    private function convertToMobileResponseArray(BookCardListResponseDTO $dtoList)
    {
        $arrayTransformed = [];

        foreach ($dtoList->getAll() as $item) {
            $arrayTransformed = $dtoList->itemToMobileScheme($item);
        }

        return [
            'books' => $arrayTransformed,
            'cnt' => $dtoList->length()
        ];
    }
}

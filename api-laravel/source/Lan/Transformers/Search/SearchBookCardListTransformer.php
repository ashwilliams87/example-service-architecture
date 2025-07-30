<?php

namespace Lan\Transformers\Search;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Search\Responses\SearchBookCardListResponseResponseDTO;

class SearchBookCardListTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToMobileResponseArray($dtoList);
    }

    private function convertToMobileResponseArray(SearchBookCardListResponseResponseDTO $dtoList)
    {
        $arrayTransformed = [];
        foreach ($dtoList->getAll() as $item) {
            $arrayTransformed[] = $dtoList->itemToMobileScheme($item);
        }
        return $arrayTransformed;
    }
}

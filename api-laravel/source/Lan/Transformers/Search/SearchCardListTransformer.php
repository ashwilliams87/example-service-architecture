<?php

namespace Lan\Transformers\Search;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Search\Responses\SearchCardDTO;
use Lan\DTOs\Search\Responses\SearchCardListResponseResponseDTO;

class SearchCardListTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToMobileResponseArray($dtoList);
    }

    private function convertToMobileResponseArray(SearchCardListResponseResponseDTO $dtoList)
    {
        $arrayTransformed = [];
        $count = 0;
        foreach ($dtoList->getAll() as $item) {
            /* @var SearchCardDTO $item */
            $arrayTransformed[] = $dtoList->itemToMobileScheme($item);
            $count += $item->getCount();
        }

        return [
            'data' => $arrayTransformed,
            'cnt' => $count
        ];
    }
}

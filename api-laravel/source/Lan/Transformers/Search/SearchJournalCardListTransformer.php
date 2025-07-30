<?php

namespace Lan\Transformers\Search;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Search\Responses\SearchJournalCardListResponseDTO;

class SearchJournalCardListTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToMobileResponseArray($dtoList);
    }

    private function convertToMobileResponseArray(SearchJournalCardListResponseDTO $dtoList)
    {
        $arrayTransformed = [];

        foreach ($dtoList->getAll() as $item) {
            $arrayTransformed[] = $dtoList->itemToMobileScheme($item);
        }

        return $arrayTransformed;
    }
}

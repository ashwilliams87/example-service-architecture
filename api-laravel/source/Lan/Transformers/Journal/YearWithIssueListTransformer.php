<?php

namespace Lan\Transformers\Journal;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Journal\Responses\JournalIssueList\YearWithIssueListDTO;
use Lan\DTOs\Journal\Responses\JournalIssueList\YearWithIssuesDTO;

class YearWithIssueListTransformer implements TransformMobile
{

    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToMobileResponseArray($dtoList);
    }

    private function convertToMobileResponseArray(YearWithIssueListDTO $dtoList)
    {
        $arrayTransformed = [];
        foreach ($dtoList->getAll() as $item) {
            /* @var YearWithIssuesDTO $item */
            $arrayTransformed[] = $dtoList->itemToMobileScheme($item);
        }

        return $arrayTransformed;
    }
}

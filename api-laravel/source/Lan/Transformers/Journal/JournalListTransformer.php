<?php

namespace Lan\Transformers\Journal;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Journal\Responses\JournalCardDTO;
use Lan\DTOs\Journal\Responses\JournalListResponseDTO;

class JournalListTransformer implements TransformMobile
{

    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToMobileResponseArray($dtoList);
    }

    private function convertToMobileResponseArray(JournalListResponseDTO $dtoList)
    {
        $arrayTransformed = [];
        foreach ($dtoList->getAll() as $item) {
            /* @var JournalCardDTO $item */
            $arrayTransformed[] = $dtoList->itemToMobileScheme($item);
        }

        return [
            'journals' => $arrayTransformed,
            'cnt' => $dtoList->getCount(),
        ];
    }
}

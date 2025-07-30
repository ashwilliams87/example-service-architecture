<?php

namespace Lan\Transformers\Journal;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Journal\Responses\ArticleCardDTO;
use Lan\DTOs\Journal\Responses\ArticleListResponseDTO;

class ArticleListTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToMobileResponseArray($dtoList);
    }

    private function convertToMobileResponseArray(ArticleListResponseDTO $dtoList)
    {
        $arrayTransformed = [];
        foreach ($dtoList->getAll() as $item) {
            /* @var ArticleCardDTO $item */
            $arrayTransformed[] = $dtoList->itemToMobileScheme($item);
        }

        return $arrayTransformed;
    }
}

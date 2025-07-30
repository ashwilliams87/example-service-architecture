<?php

namespace Lan\Transformers\Category;

use Lan\Contracts\DTOs\Category\CategoryListResponseDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class CategoryListTransformer implements TransformMobile
{

    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToMobileResponseArray($dto);
    }

    private function convertToMobileResponseArray(CategoryListResponseDTOInterface $dtoList)
    {
        $arrayTransformed = [];

        foreach ($dtoList->getAll() as $item) {
            $arrayTransformed[] = $dtoList->itemToMobileScheme($item);
        }

        return $arrayTransformed;
    }
}

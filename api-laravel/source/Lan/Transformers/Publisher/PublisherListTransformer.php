<?php

namespace Lan\Transformers\Publisher;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Publisher\PublisherListResponseDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class PublisherListTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToMobileResponseArray($dto);
    }

    private function convertToMobileResponseArray(PublisherListResponseDTOInterface $dtoList): array
    {
        $arrayTransformed = [];

        foreach ($dtoList->getAll() as $item) {
            $arrayTransformed[] = $dtoList->itemToMobileScheme($item);
        }

        return $arrayTransformed;
    }
}

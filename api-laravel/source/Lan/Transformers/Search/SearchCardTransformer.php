<?php

namespace Lan\Transformers\Search;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Search\SearchCardDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class SearchCardTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(SearchCardDTOInterface $dto): array
    {
        return [
            'id' => $dto->getId(),
            'type' =>  $dto->getType(),
            'title' => $dto->getTitle(),
            'cnt' => (string) $dto->getCount() // Использовать (string) иначе iOs ломается
        ];
    }
}

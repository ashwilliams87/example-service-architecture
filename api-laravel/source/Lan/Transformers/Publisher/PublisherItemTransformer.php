<?php

namespace Lan\Transformers\Publisher;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Publisher\Responses\PublisherDTO;

class PublisherItemTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToMobileResponseArray($dto);
    }

    private function convertToMobileResponseArray(PublisherDTO $dto)
    {
        return [
            'id' => (string)$dto->getId(), // В phalcon-api выводилось как строка
            'title' => $dto->getTitle(),
        ];
    }
}

<?php

namespace Lan\Transformers\Auth;

use Lan\Contracts\DTOs\Auth\SubscriberNameResultDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class SubscriberNameResultTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToArray($dto);
    }

    private function convertToArray(SubscriberNameResultDTOInterface $dto): array
    {
        return [
            'subscriber_name' => $dto->getSubscriberName(),
        ];
    }
}

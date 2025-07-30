<?php

namespace Lan\Transformers\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\User\UserAuthCardInterface;
use Lan\Contracts\Transformers\TransformMobile;

class UserAuthCardTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToArray($dto);
    }

    private function convertToArray(UserAuthCardInterface $dto): array
    {
        return [
            'id' => $dto->getId(),
            'x-auth-token' => $dto->getXAuthToken(),
            'name' => $dto->getName(),
            'email' => $dto->getEmail(),
            'subscriber' => $dto->getSubscriber(),
            'subscription_end_date' => !$dto->getSubscriptionEndDate()->isEmpty() ? $dto->getSubscriptionEndDate()->toDateTimeString() : null,
        ];
    }
}

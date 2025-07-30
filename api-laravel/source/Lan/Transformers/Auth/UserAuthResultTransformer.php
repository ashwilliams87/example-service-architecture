<?php

namespace Lan\Transformers\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\User\UserAuthResultDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class UserAuthResultTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToArray($dto);
    }

    private function convertToArray(UserAuthResultDTOInterface $dto): array
    {
        return [
           'User' => (new UserAuthCardTransformer())->transformToMobileScheme($dto->getUserAuthCard())
        ];
    }
}

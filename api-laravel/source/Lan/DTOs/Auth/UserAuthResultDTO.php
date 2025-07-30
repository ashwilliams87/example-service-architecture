<?php

namespace Lan\DTOs\Auth;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\User\UserAuthResultDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Auth\Responses\UserAuthCardDTO;
use Lan\Transformers\Auth\UserAuthResultTransformer;

class UserAuthResultDTO implements UserAuthResultDTOInterface
{
    public function __construct(
        private readonly RequestResultInterface $requestResult,
        private readonly UserAuthCardDTO        $userAuthCard,
    )
    {

    }

    public function getUserAuthCard(): UserAuthCardDTO
    {
        return $this->userAuthCard;
    }

    public function getHttpStatusResult(): RequestResultInterface
    {
        return $this->requestResult;
    }

    public function toMobileScheme(TransformMobile $transformer = new UserAuthResultTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}

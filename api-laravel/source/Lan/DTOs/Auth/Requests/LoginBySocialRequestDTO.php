<?php

namespace Lan\DTOs\Auth\Requests;

use Lan\Contracts\DTOs\Auth\LoginBySocialRequestDTOInterface;
class LoginBySocialRequestDTO implements LoginBySocialRequestDTOInterface
{
    public function __construct(
        private readonly string $socialNetwork,
        private readonly string $socialToken,
    )
    {

    }

    public function getSocialNetwork(): string
    {
        return $this->socialNetwork;
    }

    public function getSocialToken(): string
    {
        return $this->socialToken;
    }

    public function isValid(): bool
    {

    }
}

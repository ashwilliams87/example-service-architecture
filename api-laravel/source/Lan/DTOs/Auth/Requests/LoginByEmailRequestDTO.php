<?php

namespace Lan\DTOs\Auth\Requests;

use Lan\Contracts\DTOs\Auth\LoginByEmailRequestDTOInterface;

class LoginByEmailRequestDTO implements LoginByEmailRequestDTOInterface
{
    public function __construct(
        private readonly string $email,
        private readonly string $password,
    )
    {

    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isValid(): bool
    {
        throw new \Exception('Not implemented');
    }
}

<?php

namespace Lan\DTOs\Auth\Requests;

use Lan\Contracts\DTOs\Auth\RecoverPasswordRequestDTOInterface;

class RecoverPasswordRequestDTO implements RecoverPasswordRequestDTOInterface
{
    public function __construct(
        private readonly string $email,
    )
    {

    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}

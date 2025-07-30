<?php

namespace Lan\DTOs\Auth\Requests;

use Lan\Contracts\DTOs\Auth\RegisterUserRequestDTOInterface;

class RegisterByEmailRequestDTO implements RegisterUserRequestDTOInterface
{
    public function __construct(
        private readonly string $lastName,
        private readonly string $firstName,
        private readonly string $patronymic,
        private readonly string $email,
        private readonly string $password,
        private readonly string $ipAddress,
    )
    {

    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSocialNetwork(): string
    {
        return '';
    }

    public function getSocialToken(): string
    {
        return '';
    }

    public function getInviteCode(): string
    {
        return '';
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function isValid(): bool
    {

    }
}

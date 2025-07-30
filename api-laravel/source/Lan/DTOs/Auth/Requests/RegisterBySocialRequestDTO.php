<?php

namespace Lan\DTOs\Auth\Requests;

use Lan\Contracts\DTOs\Auth\RegisterUserRequestDTOInterface;

class RegisterBySocialRequestDTO implements RegisterUserRequestDTOInterface
{
    public function __construct(
        private readonly string $lastName,
        private readonly string $firstName,
        private readonly string $email,
        private readonly string $password,
        private readonly string $socialNetwork,
        private readonly string $socialToken,
        private readonly string $inviteCode,
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
        return '';
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
        return $this->socialNetwork;
    }

    public function getSocialToken(): string
    {
        return $this->socialToken;
    }

    public function getInviteCode(): string
    {
        return $this->inviteCode;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function isValid(): bool
    {

    }
}

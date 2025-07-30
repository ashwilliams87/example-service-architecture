<?php

namespace Lan\Contracts\DTOs\Auth;

interface RegisterUserRequestDTOInterface extends IpAddressDTOInterface
{
    public function getLastName(): string;

    public function getFirstName(): string;

    public function getPatronymic(): string;

    public function getEmail(): string;

    public function getPassword(): string;

    public function getSocialNetwork(): string;

    public function getSocialToken(): string;

    public function getInviteCode(): string;
}

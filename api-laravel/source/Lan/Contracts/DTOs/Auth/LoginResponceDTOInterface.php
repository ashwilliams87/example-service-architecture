<?php

namespace Lan\Contracts\DTOs\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;

interface LoginResponceDTOInterface extends LanDTOInterface
{

    public function getEmail(): string;

    public function getPassword(): string;

    public function isValid(): bool;
}

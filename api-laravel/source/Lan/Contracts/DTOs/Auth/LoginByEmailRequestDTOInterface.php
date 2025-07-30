<?php

namespace Lan\Contracts\DTOs\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;

interface LoginByEmailRequestDTOInterface extends LanDTOInterface
{
    public function getEmail(): string;

    public function getPassword(): string;
}

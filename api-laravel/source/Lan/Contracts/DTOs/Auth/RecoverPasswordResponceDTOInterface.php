<?php

namespace Lan\Contracts\DTOs\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;

interface RecoverPasswordResponceDTOInterface extends ResponseContainsInterface, LanDTOInterface
{
    public function getEmail(): string;
}

<?php

namespace Lan\Contracts\DTOs\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;

interface LoginBySocialRequestDTOInterface extends LanDTOInterface
{
    public function getSocialNetwork(): string;

    public function getSocialToken(): string;
}

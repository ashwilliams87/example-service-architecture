<?php
namespace Lan\Contracts\DTOs\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;

interface RecoverPasswordRequestDTOInterface extends LanDTOInterface
{
    public function getEmail(): string;
}

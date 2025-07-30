<?php

namespace Lan\Contracts\DTOs\User;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\MobileResult;
use Lan\DTOs\Auth\Responses\UserAuthCardDTO;

interface UserAuthResultDTOInterface extends LanDTOInterface, MobileResult
{
    public function getUserAuthCard(): UserAuthCardDTO;
}

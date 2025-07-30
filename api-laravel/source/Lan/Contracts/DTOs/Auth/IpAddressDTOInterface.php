<?php

namespace Lan\Contracts\DTOs\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;

interface IpAddressDTOInterface extends LanDTOInterface
{
    public function getIpAddress(): string;
}

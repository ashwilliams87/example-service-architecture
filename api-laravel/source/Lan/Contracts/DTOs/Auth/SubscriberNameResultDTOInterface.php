<?php

namespace Lan\Contracts\DTOs\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\MobileResult;

interface SubscriberNameResultDTOInterface extends LanDTOInterface, MobileResult
{
    public function getSubscriberName(): string;
}

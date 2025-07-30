<?php

namespace Lan\Contracts\DTOs\Auth;

use Lan\Contracts\DTOs\LanDTOInterface;

interface InviteRequestDTOInterface extends LanDTOInterface
{
    public function getInviteCode(): string;

    public static function create(string $inviteCode): static;

}

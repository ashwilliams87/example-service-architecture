<?php

namespace Lan\Contracts\Repositories;

interface InviteRepositoryInterface
{
    public function markInviteCodeAsUsedByNow(string $inviteCode);
}

<?php

namespace Lan\Repositories;

use Ebs\Model\Invite;
use Ice\Helper\Date;
use Lan\Contracts\Repositories\InviteRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;

class InviteRepository implements InviteRepositoryInterface
{
    public function __construct(
        protected SecurityServiceInterface $securityService,
    )
    {

    }

    public function markInviteCodeAsUsedByNow(string $inviteCode): void
    {
        Invite::createQueryBuilder()
            ->eq(['invite' => $inviteCode])
            ->getUpdateQuery(['/used_at' => Date::get(null, Date::FORMAT_MYSQL)])
            ->getQueryResult();
    }
}

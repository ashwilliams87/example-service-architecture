<?php

namespace Lan\Repositories;

use Ebs\Helper\GraphQl\GraphQlClient;
use Ebs\Model\Invite;
use Ebs\Model\Subscriber;
use Ice\Core\Request;
use Ice\Helper\Date;
use Ice\Model\User;
use Lan\Contracts\Repositories\SubscriberRepositoryInterface;

class SubscriberRepository implements SubscriberRepositoryInterface
{
    public function getSubscriberByIp(string $clientIp): Subscriber
    {
        $subscriber = GraphQlClient::getSubscriberFromIp(Request::ip());

        /** @var Subscriber $subscriber */
        if ($subscriber) {
            return $subscriber;
        }

        return Subscriber::create();
    }
    public function getSubscriberByInvite(string $inviteCode): Subscriber
    {
        $subscriber = Subscriber::createQueryBuilder()
            ->inner(User::class)
            ->inner(Invite::class)
            ->is('/active')
            ->eq(['invite' => $inviteCode], Invite::class)
            ->isNull('/used_at', Invite::class)
            ->gt('/expired', Date::get('', Date::FORMAT_MYSQL_DATE), Invite::class)
            ->getSelectQuery('*')
            ->getModel();

        /** @var Subscriber $subscriber */
        if ($subscriber) {
            return $subscriber;
        }

        return Subscriber::create();
    }
}

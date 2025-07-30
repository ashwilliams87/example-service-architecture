<?php
namespace Lan\Contracts\Repositories;

use Ebs\Model\Subscriber;

interface SubscriberRepositoryInterface
{

    public function getSubscriberByIp(string $clientIp): Subscriber;

    public function getSubscriberByInvite(string $inviteCode): Subscriber;
}

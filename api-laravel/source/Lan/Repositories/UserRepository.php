<?php

namespace Lan\Repositories;

use Ebs\Helper\GraphQl\GraphQlClient;
use Ebs\Model\Group;
use Ebs\Model\Subscriber;
use Ice\Core\DataSource;
use Ice\Core\Request;
use Ice\Helper\Date;
use Ice\Model\User;
use Lan\Contracts\Repositories\InviteRepositoryInterface;
use Lan\Contracts\Repositories\SubscriberRepositoryInterface;
use Lan\Contracts\Repositories\UserRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected SecurityServiceInterface $securityService,
        protected SubscriberRepositoryInterface $subscriberRepository,
        protected InviteRepositoryInterface $inviteRepository,
    )
    {

    }

    public function setUserByApp(int $userId): void
    {
        User::createQueryBuilder()
            ->eq(['security_user__fk' => $userId])
            ->getUpdateQuery([
                'app_used' => 1,
            ])->getQueryResult();
    }

    public function getSubscriberByIp(string $clientIp): Subscriber
    {
        $subscriber = GraphQlClient::getSubscriberFromIp(Request::ip());

        /** @var Subscriber $subscriber */
        if ($subscriber) {
            return $subscriber;
        }

        return Subscriber::create();
    }

    public function getAuthenticatedUser(): User
    {
        $user = $this->securityService->getUser();
        if (!$user) {
            return User::create();
        }
        return $user;
    }

    public function getUserByUserKey(string $userKey): User
    {
        $userPk = User::getSelectQuery('/pk', ['security_user__fk' => $userKey])->getValue();
        if ($userPk) {
            return User::getModel($userPk, '*');
        }

        return User::create();
    }

    public function getOrCreateMobileUserByUserKey(string $userKey, string $inviteCode = ''): User
    {
        $user = $this->getUserByUserKey($userKey);
        if($user->getPk()){
            return $user;
        }

        $subscriber = $this->subscriberRepository->getSubscriberByInvite($inviteCode);

        $subscriberPk = null;
        $autoConfirmation = 0;

        if ($subscriber) {
            $subscriberPk = $subscriber->getPkValue();
            $autoConfirmation = 1; // по ip, invite, domain (см. Ebs:Api_V2_Auth_UserCreate)
        }

        $dataSource = DataSource::getInstance();
        try {
            $dataSource->beginTransaction();

            $user = User::create([
                'roles' => serialize([Group::STUDENT_MOBILE]),
                '/expired_at' => Date::get('+1 year'),
                'subscriber__fk' => ($autoConfirmation ? $subscriberPk : null),
                'previous_subscriber_id' => $subscriberPk,
                'security_user__fk' => $userKey,
                'app_registration' => 1
            ])->save();

            $this->inviteRepository->markInviteCodeAsUsedByNow($inviteCode);

            $dataSource->commitTransaction();

            return $user;
        } catch (\Throwable $e) {
            $dataSource->rollbackTransaction($e);
            throw $e;
        }
    }

    public function getAuthenticatedUserExpiredDate(): string
    {
        return $this->getUserExpiredDate($this->securityService->getUser());
    }

    public function getUserExpiredDate(User $user): string
    {
        return $user->get('/expired_at');
    }

    public function isAuthenticatedUserActive(): bool
    {
        return !Date::expired($this->getAuthenticatedUserExpiredDate());
    }

    public function isUserActive(User $user): bool
    {
        return !Date::expired($this->getUserExpiredDate($user));
    }
}

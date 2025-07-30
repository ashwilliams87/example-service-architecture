<?php

namespace Lan\Contracts\Repositories;

use Ebs\Model\Subscriber;
use Ice\Model\User;

interface UserRepositoryInterface
{
    public function setUserByApp(int $userId): void;

    public function getSubscriberByIp(string $clientIp): Subscriber;

    public function getAuthenticatedUser(): User;

    public function getAuthenticatedUserExpiredDate(): string;

    public function getUserExpiredDate(User $user): string;

    public function isAuthenticatedUserActive(): bool;
    public function isUserActive(User $user): bool;

    public function getUserByUserKey(string $userKey): User;

    public function getOrCreateMobileUserByUserKey(string $userKey, string $inviteCode = ''): User;
}

<?php

namespace Lan\Contracts\Services\Security;

use Ebs\Model\Subscriber;
use Ice\Model\User;
use Lan\Security\Security\Jwt;

interface SecurityServiceInterface
{
    public function getUser(): ?User;

    public function getSubscriber(): ?Subscriber;

    public function getUniqueUser(): ?string;

    public function getSessionId(): string;

    public function getJwt(): Jwt;

    public function getAccessToken(): string;

    public function isAuth(): bool;

    public function callSecurityRoute($routeName, array $params = []): string|array;

    public function checkIfUserInSubscribeReaderGroup(): bool;

    public function checkIfUserInGuestGroup(): bool;
}

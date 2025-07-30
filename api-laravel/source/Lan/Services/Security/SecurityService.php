<?php

namespace Lan\Services\Security;

use Ebs\Model\Group;
use Ebs\Model\Subscriber;
use Ebs\Security\Ebs as EbsSecurity;
use Ice\Core\Security;
use Ice\Model\User;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\Security\Helper\Security as SecurityHelper;
use Lan\Security\Security\Jwt;

class SecurityService implements SecurityServiceInterface
{
    private Security $security;

    public function __construct()
    {
         $this->security = EbsSecurity::getInstance();
    }

    public function getUser(): User
    {
        return $this->security->getUser();
    }

    public function getSubscriber(): ?Subscriber
    {
        return $this->security->getSubscriber();
    }

    public function getUniqueUser(): ?string
    {
        return $this->security->getUniqueUser();
    }

    public function getSessionId(): string
    {
        return $this->security->getSessionId();
    }

    public function getJwt(): Jwt
    {
        return $this->security->getJwt();
    }

    public function getAccessToken(): string
    {
        return $this->getJwt()->getAccessToken();
    }

    public function isAuth(): bool
    {
        return $this->security->isAuth();
    }

    public function callSecurityRoute($routeName, array $params = []): string|array
    {
        return SecurityHelper::call($routeName, $params);
    }


    public function checkIfUserInSubscribeReaderGroup(): bool
    {
        return $this->security->check([Group::SUBSCRIBER_READER]);
    }

    public function checkIfUserInGuestGroup(): bool
    {
        return $this->security->check([Group::GUEST]);
    }
}

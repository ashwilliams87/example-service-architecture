<?php

namespace App\Service;

use App\Contract\Service\SecurityServiceInterface;
use Ebs\Helper\GraphQl\GraphQlClient;
use Ebs\Security\Ebs;
use Ice\Core\Debuger;
use Ice\Core\Request;

class SecurityService implements SecurityServiceInterface
{
    private $security;

    public function __construct()
    {
        $this->security = Ebs::getInstance();
    }

    public function isAuth(): bool
    {
        return $this->security->isAuth();
    }

    public function getSubscriberId(): int
    {
        return $this->security->getSubscriber()->getPkValue();
    }

    public function login(): void
    {
        $responseIceAccountModel = GraphQlClient::getMoodleAccountByMoodleToken(Request::getHeader('x-auth-token-moodle'));

        if (!empty($responseIceAccountModel)) {
            $this->security->login($responseIceAccountModel, []);
        }

    }

    public function getUser()
    {
        $this->security->getUser();
    }

    public function isReader(): bool
    {
        return $this->security->isReader();
    }
}
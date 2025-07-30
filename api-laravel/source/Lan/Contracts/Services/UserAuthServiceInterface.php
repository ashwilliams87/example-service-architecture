<?php

namespace Lan\Contracts\Services;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\Auth\InviteRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\IpAddressDTOInterface;
use Lan\Contracts\DTOs\Auth\LoginBySocialRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\LoginByEmailRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\RecoverPasswordRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\RegisterUserRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\SubscriberNameResultDTOInterface;
use Lan\Contracts\DTOs\User\UserAuthResultDTOInterface;

interface UserAuthServiceInterface
{
    public function logInByEmail(LoginByEmailRequestDTOInterface $requestDTO): UserAuthResultDTOInterface;

    public function logInBySocial(LoginBySocialRequestDTOInterface $requestDTO): UserAuthResultDTOInterface;

    public function logOut(): RequestResultInterface;

    public function registerByEmail(RegisterUserRequestDTOInterface $requestDTO): RequestResultInterface;

    public function registerBySocial(RegisterUserRequestDTOInterface $requestDTO): UserAuthResultDTOInterface;

    public function recoverPassword(RecoverPasswordRequestDTOInterface $requestDTO): RequestResultInterface;

    public function getSubscriberNameByIp(IpAddressDTOInterface $ipAddressDTO): SubscriberNameResultDTOInterface;

    public function getSubscriberNameByInvite(InviteRequestDTOInterface $inviteRequestDTO): SubscriberNameResultDTOInterface;

    public function deactivateUser(): RequestResultInterface;
}

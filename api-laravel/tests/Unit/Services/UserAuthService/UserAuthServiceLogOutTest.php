<?php


namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Lan\DataTypes\RequestResult\Error\CustomHttpResult;
use Lan\DataTypes\RequestResult\Error\LogOut\LogOutError;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceLogOutTest extends Unit
{
    protected UnitTester $tester;

    public function testLogOut(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function () {
                return ['code' => 200];
            },
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, []);

        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
            'getSubscriberByInvite' => Expected::never(),
        ]);

        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::never(),
        ]);

        $userService = new UserAuthService(
            securityService: $securityServiceMock,
            userRepository: $userRepositoryMock,
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $status = $userService->logOut();
        $this->assertEquals(SuccessOk200::create(), $status);
    }

    public function testFailedLogOutWithCodeAndStatusFromSecurityCall(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function () {
                return [
                    'code' => 400,
                    'error' => 'Bad Request'
                ];
            },
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, []);

        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
            'getSubscriberByInvite' => Expected::never(),
        ]);


        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::never(),
        ]);

        $userService = new UserAuthService(
            securityService: $securityServiceMock,
            userRepository: $userRepositoryMock,
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $status = $userService->logOut();
        $this->assertInstanceOf(CustomHttpResult::class, $status);
        $this->assertEquals(400, $status->getStatusCode());
        $this->assertEquals('Bad Request', $status->getMessage());
    }

    public function testFailedLogOutWithLogOutError(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function () {
                return [
                    'code' => 500,
                ];
            },
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, []);

        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
            'getSubscriberByInvite' => Expected::never(),
        ]);

        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::never(),
        ]);

        $userService = new UserAuthService(
            securityService: $securityServiceMock,
            userRepository: $userRepositoryMock,
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $status = $userService->logOut();
        $this->assertEquals(LogOutError::create(), $status);
    }
}

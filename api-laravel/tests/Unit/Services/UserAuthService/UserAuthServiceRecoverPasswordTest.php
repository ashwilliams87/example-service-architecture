<?php


namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Lan\DataTypes\RequestResult\Error\BadRequest;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Auth\Requests\RecoverPasswordRequestDTO;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceRecoverPasswordTest extends Unit
{
    protected UnitTester $tester;

    public function testLogOut(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function () {
                return ['code' => 200];
            },
        ]);

        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
            'getSubscriberByInvite' => Expected::never(),
        ]);

        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::never(),
        ]);

        $userService = new UserAuthService(
            securityService: $securityServiceMock,
            userRepository: $this->make(UserRepository::class),
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $status = $userService->recoverPassword(new RecoverPasswordRequestDTO('test@test.com'));

        $this->assertInstanceOf(SuccessOk200::class, $status);
        $this->assertEquals('Ok', $status->getMessage());
        $this->assertEquals(200, $status->getStatusCode());
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

        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
            'getSubscriberByInvite' => Expected::never(),
        ]);


        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::never(),
        ]);

        $userService = new UserAuthService(
            securityService: $securityServiceMock,
            userRepository: $this->make(UserRepository::class),
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $status = $userService->recoverPassword(new RecoverPasswordRequestDTO('test.notvalid@test.com'));

        $this->assertInstanceOf(BadRequest::class, $status);
        $this->assertEquals(400, $status->getStatusCode());
        $this->assertEquals('Bad Request', $status->getMessage());
    }
}

<?php

namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ebs\Model\Subscriber;
use Lan\DataTypes\RequestResult\Error\Unauthorized;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Auth\Requests\InviteRequestDTO;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceGetSubscriberNameByInviteTest extends Unit
{
    protected UnitTester $tester;

    public function testGetSubscriberByInviteWithValidCode(): void
    {
        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByInvite' => Expected::once(function () {
                return $this->make(Subscriber::class, [
                    'get' => Expected::once(function ($key) {
                        if ($key === '/name') {
                            return 'Test Subscriber';
                        }
                        return null;
                    }),
                    'getPk' => Expected::once(1),
                ]);
            }),
        ]);

        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::never(),
        ]);

        $userService = new UserAuthService(
            securityService: $this->make(SecurityService::class),
            userRepository: $this->make(UserRepository::class),
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $resultDTO = $userService->getSubscriberNameByInvite(new InviteRequestDTO('invite_code'));

        $this->assertInstanceOf(SuccessOk200::class, $resultDTO->getHttpStatusResult());
        $this->assertEquals(200, $resultDTO->getHttpStatusResult()->getStatusCode());
        $this->assertEquals('Ok', $resultDTO->getHttpStatusResult()->getMessage());
        $this->assertEquals('Test Subscriber', $resultDTO->getSubscriberName());
    }

    public function testGetSubscriberByIpWithInviteWithNonValidCode(): void
    {
        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByInvite' => Expected::once(Subscriber::create()),
        ]);

        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::never(),
        ]);

        $userService = new UserAuthService(
            securityService: $this->make(SecurityService::class),
            userRepository: $this->make(UserRepository::class),
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $resultDTO = $userService->getSubscriberNameByInvite(new InviteRequestDTO('invite_code'));

        $this->assertInstanceOf(Unauthorized::class, $resultDTO->getHttpStatusResult());
        $this->assertEquals(401, $resultDTO->getHttpStatusResult()->getStatusCode());
        $this->assertEquals('Инвайт код не найден или уже был использован.', $resultDTO->getHttpStatusResult()->getMessage());
        $this->assertEquals('', $resultDTO->getSubscriberName());
    }
}

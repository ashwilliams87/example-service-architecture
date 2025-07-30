<?php

namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ebs\Model\Subscriber;
use Lan\DataTypes\RequestResult\Error\CheckIp\IpNotFound;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Auth\Requests\IpRequestDTO;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceGetSubscriberNameByIpTest extends Unit
{
    protected UnitTester $tester;

    public function testGetSubscriberByIpWithValidIp(): void
    {
        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::once(function () {
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

        $responseDTO = $userService->getSubscriberNameByIp(new IpRequestDTO('90.90.90.90'));
        $this->assertEquals(SuccessOk200::create(), $responseDTO->getHttpStatusResult());
        $this->assertEquals('Test Subscriber', $responseDTO->getSubscriberName());
    }

    public function testGetSubscriberByIpWithNonValidIp(): void
    {
        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::once(Subscriber::create()),
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

        $responseDTO = $userService->getSubscriberNameByIp(new IpRequestDTO('80.80.80.80'));
        $this->assertEquals(new IpNotFound(), $responseDTO->getHttpStatusResult());
        $this->assertEquals('', $responseDTO->getSubscriberName());
    }
}

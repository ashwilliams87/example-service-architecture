<?php


namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ice\Model\User;
use Lan\DataTypes\RequestResult\Error\CustomHttpResult;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Auth\Requests\RegisterByEmailRequestDTO;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceRegisterByEmailTest extends Unit
{
    protected UnitTester $tester;

    public function testSuccessfulRegisterByEmail(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function (string $routeName) {
                self::assertEquals('security_signup', $routeName);
                return [
                    'code' => 200,
                    'jwt' => [
                        'access_token' => 'test_token',
                        'data' => [
                            'payload' => [
                                'user' => [
                                    'id' => 1,
                                    'surname' => 'Test',
                                    'name' => 'User',
                                    'patronymic' => '',
                                    'email' => 'test@example.com'
                                ]
                            ]
                        ],
                    ],
                    'user_key' => 'test_user_key'
                ];
            },
            'getSubscriber' => Expected::never(),
            'getUser' => Expected::never(),
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, [
            'setUserByApp' => Expected::never(),
            'getOrCreateMobileUserByUserKey' => Expected::once(function () {
                return User::create();
            }),
            'getAuthenticatedUserExpiredDate' => Expected::never(),
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
            userRepository: $userRepositoryMock,
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $requestResult = $userService->registerByEmail(new RegisterByEmailRequestDTO(
            lastName: 'Testov',
            firstName: 'Tester',
            patronymic: 'Testovich',
            email: 'test@example.com',
            password: '12345678',
            ipAddress: '80.80.80.80'
        ));

        $this->assertInstanceOf(SuccessOk200::class, $requestResult);
        $this->assertEquals(200, $requestResult->getStatusCode());
        $this->assertEquals('Ok', $requestResult->getMessage());
    }

    public function testErrorStatusRegisterByEmail(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function (string $routeName) {
                self::assertEquals('security_signup', $routeName);
                return ['code' => 412, 'user_key' => 'test_user_key', 'error' => 'Статус: Ошибка', 'status'=>'error'];
            },
            'getSubscriber' => Expected::never(),
            'getUser' => Expected::never(),
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, [
            'setUserByApp' => Expected::never(),
            'getOrCreateMobileUserByUserKey' => Expected::never(),
            'getAuthenticatedUserExpiredDate' => Expected::never(),
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
            userRepository: $userRepositoryMock,
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $requestResult = $userService->registerByEmail(new RegisterByEmailRequestDTO(
            lastName: 'Testov',
            firstName: 'Tester',
            patronymic: 'Testovich',
            email: 'test@example.com',
            password: '123456781234123412341234123412341234123412341',
            ipAddress: '80.80.80.80'
        ));

        $this->assertInstanceOf(CustomHttpResult::class, $requestResult);
        $this->assertEquals(412, $requestResult->getStatusCode());
        $this->assertEquals('Статус: Ошибка', $requestResult->getMessage());
    }

    public function testFailedRegisterByEmail(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function (string $routeName) {
                self::assertEquals('security_signup', $routeName);
                return ['code' => 400, 'user_key' => 'test_user_key', 'error' => 'Ошибка'];
            },
            'getSubscriber' => Expected::never(),
            'getUser' => Expected::never(),
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, [
            'setUserByApp' => Expected::never(),
            'getOrCreateMobileUserByUserKey' => Expected::once(function () {
                return User::create();
            }),
            'getAuthenticatedUserExpiredDate' => Expected::never(),
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
            userRepository: $userRepositoryMock,
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $requestResult = $userService->registerByEmail(new RegisterByEmailRequestDTO(
            lastName: 'Testov',
            firstName: 'Tester',
            patronymic: 'Testovich',
            email: 'test@example.com',
            password: '123456781234123412341234123412341234123412341',
            ipAddress: '80.80.80.80'
        ));

        $this->assertInstanceOf(CustomHttpResult::class, $requestResult);
        $this->assertEquals(400, $requestResult->getStatusCode());
        $this->assertEquals('Ошибка', $requestResult->getMessage());
    }
}

<?php


namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ebs\Model\Subscriber;
use Ice\Exception\Security_User_NotFound;
use Ice\Model\User;
use Lan\DataTypes\EbsCarbon;
use Lan\DataTypes\RequestResult\Error\LogIn\AccountNotFoundError;
use Lan\DTOs\Auth\Requests\LoginByEmailRequestDTO;
use Lan\DTOs\Auth\Responses\UserAuthCardDTO;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceLogInByEmailTest extends Unit
{
    protected UnitTester $tester;

    public function testSuccessfulLogInByEmail(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function () {
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
            'getSubscriber' => Expected::once(function () {
                return $this->make(Subscriber::class, [
                    'get' => Expected::once(function ($key) {
                        if ($key === '/name') {
                            return 'Test Subscriber';
                        }

                        return null;
                    }),
                    'getPk' => Expected::never(),
                    'getSubscriberName' => Expected::once('Test Subscriber'),
                ]);
            }),
            'getUser' => Expected::once(User::create(['\pk' => 11534980])),
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, [
            'setUserByApp' => Expected::once(),
            'getOrCreateMobileUserByUserKey' => Expected::once(User::create()),
            'getAuthenticatedUserExpiredDate' => Expected::once('2024-07-18 11:06:35')
        ]);

        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
            'getSubscriberByInvite' => Expected::never(),
        ]);

        $expectedUserDTO = UserAuthCardDTO::create(
            id: 1,
            xAuthToken: 'test_token',
            name: 'Test User ',
            email: 'test@example.com',
            subscriber: 'Test Subscriber',
            subscriptionEndDate: EbsCarbon::parse('2024-07-18 11:06:35')
        );

        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::never(),
        ]);

        $userService = new UserAuthService(
            securityService: $securityServiceMock,
            userRepository: $userRepositoryMock,
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $result = $userService->logInByEmail(new LoginByEmailRequestDTO('test@example.com', 'password'));

        $this->assertEquals(200, $result->getHttpStatusResult()->getStatusCode());
        $this->assertEquals($expectedUserDTO, $result->getUserAuthCard());
    }

    public function testFailedLogInByEmailWithWrongPassword(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => Expected::once(['code' => 401]),
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

        $result = $userService->logInByEmail(new LoginByEmailRequestDTO('test@example.com', 'wrong_password'));

        $this->assertEquals(401, $result->getHttpStatusResult()->getStatusCode());
        $this->assertInstanceOf(AccountNotFoundError::class, $result->getHttpStatusResult());
        $this->assertTrue($result->getUserAuthCard()->isEmpty());
    }

    public function testFailedLogInByEmailWithAccountNonFound(): void
    {
        $loginRequestDTO = new LoginByEmailRequestDTO('nonexistent@example.com', 'password');

        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => Expected::once(['code' => 401])
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

        $result = $userService->logInByEmail($loginRequestDTO);

        $this->assertEquals(401, $result->getHttpStatusResult()->getStatusCode());
        $this->assertTrue($result->getUserAuthCard()->isEmpty());
    }

    public function testFailedLoginWithSecurityUserNotFoundException(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function (): void {
                throw new Security_User_NotFound('User not found', [], null, null, null, 0);
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

        $result = $userService->logInByEmail(new LoginByEmailRequestDTO('nonexistent@example.com', 'password'));

        $this->assertEquals(401, $result->getHttpStatusResult()->getStatusCode());
        $this->assertTrue($result->getUserAuthCard()->isEmpty());
    }

    public function testFailedLoginWithCustomError(): void
    {
        $loginRequestDTO = new LoginByEmailRequestDTO('test@example.com', 'password');

        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function () {
                return [
                    'code' => 500,
                    'error' => 'Internal Server Error'
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

        $result = $userService->logInByEmail($loginRequestDTO);

        $this->assertEquals(500, $result->getHttpStatusResult()->getStatusCode());
        $this->assertEquals('Internal Server Error', $result->getHttpStatusResult()->getMessage());
        $this->assertTrue($result->getUserAuthCard()->isEmpty());
    }
}

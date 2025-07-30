<?php


namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ebs\Model\Subscriber;
use Ice\Exception\Security_User_NotFound;
use Ice\Model\User;
use Lan\DataTypes\EbsCarbon;
use Lan\DataTypes\RequestResult\Error\LogIn\AccountNotFoundError;
use Lan\DTOs\Auth\Requests\LoginBySocialRequestDTO;
use Lan\DTOs\Auth\Responses\UserAuthCardDTO;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceLogInBySocialTest extends Unit
{
    protected UnitTester $tester;

    public function testSuccessfulLogInBySocial(): void
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
                    'get' => function ($key) {
                        if ($key === '/name') {
                            return 'Test Subscriber';
                        }

                        return null;
                    },
                    'getPk' => 1,
                    'getSubscriberName' => 'Test Subscriber',
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

        $result = $userService->LogInBySocial(new LoginBySocialRequestDTO(
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO'
        ));

        $this->assertEquals(200, $result->getHttpStatusResult()->getStatusCode());
        $this->assertEquals($expectedUserDTO, $result->getUserAuthCard());
    }

    public function testFailedLogInBySocialWithNonExistAccount(): void
    {
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

        $result = $userService->LogInBySocial(new LoginBySocialRequestDTO(
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO'
        ));

        $this->assertEquals(401, $result->getHttpStatusResult()->getStatusCode());
        $this->assertInstanceOf(AccountNotFoundError::class, $result->getHttpStatusResult());
        $this->assertTrue($result->getUserAuthCard()->isEmpty());
    }

    public function testFailedLogInByEmailWithAccountNonFound(): void
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

        $result = $userService->LogInBySocial(new LoginBySocialRequestDTO(
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO'
        ));

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

        $result = $userService->LogInBySocial(new LoginBySocialRequestDTO(
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO'
        ));

        $this->assertEquals(401, $result->getHttpStatusResult()->getStatusCode());
        $this->assertTrue($result->getUserAuthCard()->isEmpty());
    }

    public function testFailedLoginWithCustomError(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => Expected::once([
                    'code' => 500,
                    'error' => 'Internal Server Error'
                ]),
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

        $result = $userService->LogInBySocial(new LoginBySocialRequestDTO(
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO'
        ));

        $this->assertEquals(500, $result->getHttpStatusResult()->getStatusCode());
        $this->assertEquals('Internal Server Error', $result->getHttpStatusResult()->getMessage());
        $this->assertTrue($result->getUserAuthCard()->isEmpty());
    }
}

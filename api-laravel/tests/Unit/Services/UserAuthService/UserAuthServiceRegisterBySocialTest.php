<?php


namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ebs\Model\Subscriber;
use Ice\Model\User;
use Lan\DataTypes\RequestResult\Error\CustomHttpResult;
use Lan\DataTypes\RequestResult\Error\MethodNotAllowedError;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Auth\Requests\RegisterBySocialRequestDTO;
use Lan\DTOs\Auth\UserAuthResultDTO;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceRegisterBySocialTest extends Unit
{
    protected UnitTester $tester;

    public function testSuccessfulRegisterBySocial(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function (string $routeName) {
                self::assertEquals('ebs_security_sign_in_account', $routeName);
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
            'getSubscriber' => Expected::once(Subscriber::create()),
            'getUser' => Expected::never(),
            'getAccessToken' => Expected::once('test_token'),
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, [
            'setUserByApp' => Expected::once(),
            'getOrCreateMobileUserByUserKey' => Expected::once(User::create()),
            'getAuthenticatedUserExpiredDate' => Expected::once('10-10-2022'),
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

        $resultDTO = $userService->registerBySocial(new RegisterBySocialRequestDTO(
            lastName: 'Testov',
            firstName: 'Tester',
            email: 'test@example.com',
            password: '12345678',
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO',
            inviteCode: 'invite_code',
            ipAddress: '80.80.80.80'
        ));

        $this->assertInstanceOf(UserAuthResultDTO::class, $resultDTO);
        $this->assertInstanceOf(SuccessOk200::class, $resultDTO->getHttpStatusResult());
        $this->assertEquals(200, $resultDTO->getHttpStatusResult()->getStatusCode());
        $this->assertEquals('Ok', $resultDTO->getHttpStatusResult()->getMessage());
    }

    public function testRegisterBySocialWithNonValidIpAndInviteCode(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => Expected::never(),
            'getSubscriber' => Expected::never(),
            'getUser' => Expected::never(),
        ]);

        $userRepositoryMock = $this->make(UserRepository::class, [
            'setUserByApp' => Expected::never(),
            'getOrCreateMobileUserByUserKey' => Expected::never(),
            'getAuthenticatedUserExpiredDate' => Expected::never(),
        ]);

        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::once(function (){
                return Subscriber::create();
            }),
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

        $resultDTO = $userService->registerBySocial(new RegisterBySocialRequestDTO(
            lastName: 'Testov',
            firstName: 'Tester',
            email: 'test@example.com',
            password: '12345678',
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO',
            inviteCode: '',
            ipAddress: '80.80.80.80'
        ));

        $this->assertInstanceOf(UserAuthResultDTO::class, $resultDTO);
        $this->assertInstanceOf(MethodNotAllowedError::class, $resultDTO->getHttpStatusResult());
        $this->assertEquals(405, $resultDTO->getHttpStatusResult()->getStatusCode());
        $this->assertEquals('Для данного типа регистрации необходимо зайти в сеть вашей библиотеки', $resultDTO->getHttpStatusResult()->getMessage());
    }

    public function testErrorStatusRegisterBySocial(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function (string $routeName) {
                self::assertEquals('ebs_security_sign_in_account', $routeName);
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
            'getSubscriberByIp' =>  Expected::never(),
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

        $resultDTO = $userService->registerBySocial(new RegisterBySocialRequestDTO(
            lastName: 'Testov',
            firstName: 'Tester',
            email: 'test@example.com',
            password: '12345678',
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO',
            inviteCode: 'invite_code',
            ipAddress: '80.80.80.80'
        ));

        $this->assertInstanceOf(UserAuthResultDTO::class, $resultDTO);
        $this->assertInstanceOf(CustomHttpResult::class, $resultDTO->getHttpStatusResult());
        $this->assertEquals(412, $resultDTO->getHttpStatusResult()->getStatusCode());
        $this->assertEquals('Статус: Ошибка', $resultDTO->getHttpStatusResult()->getMessage());
    }

    public function testFailedRegisterBySocial(): void
    {
        $securityServiceMock = $this->make(SecurityService::class, [
            'callSecurityRoute' => function (string $routeName) {
                self::assertEquals('ebs_security_sign_in_account', $routeName);
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
            'getSubscriberByIp' =>  Expected::never(),
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

        $resultDTO = $userService->registerBySocial(new RegisterBySocialRequestDTO(
            lastName: 'Testov',
            firstName: 'Tester',
            email: 'test@example.com',
            password: '12345678',
            socialNetwork: 'apple',
            socialToken: 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjABCDEFG1234567890HIJKLMNOPQRS1234567TUVXYZ8901234ABCDEFGHIJKL56789MNO',
            inviteCode: 'invite_code',
            ipAddress: '80.80.80.80'
        ));

        $this->assertInstanceOf(UserAuthResultDTO::class, $resultDTO);
        $this->assertInstanceOf(CustomHttpResult::class, $resultDTO->getHttpStatusResult());
        $this->assertEquals(400, $resultDTO->getHttpStatusResult()->getStatusCode());
        $this->assertEquals('Ошибка', $resultDTO->getHttpStatusResult()->getMessage());
    }
}

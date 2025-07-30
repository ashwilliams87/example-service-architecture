<?php


namespace Tests\Unit\Services\UserAuthService;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ice\Model\Log_Message;
use Lan\DataTypes\RequestResult\Error\NoAccessToResourceError;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\MailService;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserAuthService;
use Tests\Support\UnitTester;

class UserAuthServiceDeactivateUserTest extends Unit
{
    protected UnitTester $tester;

    public function testDeactivateUserWithPermittedUser(): void
    {
        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'checkIfUserInSubscribeReaderGroup' => Expected::once(false),
            'checkIfUserInGuestGroup' => Expected::once(false),
        ]);

        $mailServiceMock = $this->make(MailService::class, [
            'sendCurrentUserDeactivationRequestEmail' => Expected::once(Log_Message::create()),
        ]);

        $userService = new UserAuthService(
            securityService: $securityServiceMock,
            userRepository: $this->make(UserRepository::class),
            subscriberRepository: $subscriberRepositoryMock,
            mailService: $mailServiceMock
        );

        $result = $userService->deactivateUser();
        $this->assertEquals(SuccessOk200::create(), $result);
    }

    public function testDeactivateUserWithUserInSubscribeReaderGroup(): void
    {
        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'checkIfUserInSubscribeReaderGroup' => Expected::once(true),
            'checkIfUserInGuestGroup' => Expected::never(),
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

        $result = $userService->deactivateUser();
        $this->assertEquals(NoAccessToResourceError::create('Такова нельзя делать пользователя'), $result);
    }

    public function testDeactivateUserWithUserInGuestGroup(): void
    {
        $subscriberRepositoryMock = $this->make(SubscriberRepository::class, [
            'getSubscriberByIp' => Expected::never(),
        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'checkIfUserInSubscribeReaderGroup' => Expected::once(false),
            'checkIfUserInGuestGroup' => Expected::once(true),
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

        $result = $userService->deactivateUser();
        $this->assertEquals(NoAccessToResourceError::create('Мы не можем нарушить закон гостеприимства и удалить гостя.'), $result);
    }
}

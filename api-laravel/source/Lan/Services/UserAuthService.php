<?php

namespace Lan\Services;

use Ice\Core\Request;
use Ice\Exception\Security_User_NotFound;
use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\Auth\InviteRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\IpAddressDTOInterface;
use Lan\Contracts\DTOs\Auth\LoginByEmailRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\LoginBySocialRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\RecoverPasswordRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\RegisterUserRequestDTOInterface;
use Lan\Contracts\DTOs\Auth\SubscriberNameResultDTOInterface;
use Lan\Contracts\DTOs\User\UserAuthResultDTOInterface;
use Lan\Contracts\Repositories\SubscriberRepositoryInterface;
use Lan\Contracts\Repositories\UserRepositoryInterface;
use Lan\Contracts\Services\MailServiceInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\Contracts\Services\UserAuthServiceInterface;
use Lan\DataTypes\EbsCarbon;
use Lan\DataTypes\RequestResult\Error\BadRequest;
use Lan\DataTypes\RequestResult\Error\CheckIp\IpNotFound;
use Lan\DataTypes\RequestResult\Error\CustomHttpResult;
use Lan\DataTypes\RequestResult\Error\LogIn\AccountNotFoundError;
use Lan\DataTypes\RequestResult\Error\LogOut\LogOutError;
use Lan\DataTypes\RequestResult\Error\MethodNotAllowedError;
use Lan\DataTypes\RequestResult\Error\NoAccessToResourceError;
use Lan\DataTypes\RequestResult\Error\Unauthorized;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Auth\Responses\SubscriberNameResultDTO;
use Lan\DTOs\Auth\Responses\UserAuthCardDTO;
use Lan\DTOs\Auth\UserAuthResultDTO;

class UserAuthService implements UserAuthServiceInterface
{
    public function __construct(
        private SecurityServiceInterface      $securityService,
        private UserRepositoryInterface       $userRepository,
        private SubscriberRepositoryInterface $subscriberRepository,
        private MailServiceInterface          $mailService,
    )
    {

    }

    public function logInByEmail(LoginByEmailRequestDTOInterface $requestDTO): UserAuthResultDTOInterface
    {
        return $this->logIn([
            'account_type' => 'password',
            'username' => $requestDTO->getEmail(),
            'password' => $requestDTO->getPassword(),
        ]);
    }

    public function logInBySocial(LoginBySocialRequestDTOInterface $requestDTO): UserAuthResultDTOInterface
    {
        return $this->logIn([
            'account_type' => 'social',
            'network' => $requestDTO->getSocialNetwork(),
            'token' => $requestDTO->getSocialToken(),
            'issuer' => Request::host(),
            //0 кейс, 1 sign in, 2 sign up по задумке
            'force_sign' => 1
        ]);
    }

    public function logOut(): RequestResultInterface
    {
        $securityResponse = $this->securityService->callSecurityRoute('ebs_security_sign_out');
        if ((int)$securityResponse['code'] === 200) {
            return SuccessOk200::create();
        }

        if (isset($securityResponse['code']) && isset($securityResponse['error'])) {
            return CustomHttpResult::create($securityResponse['error'], (int)$securityResponse['code']);
        }

        return LogOutError::create();
    }

    public function recoverPassword(RecoverPasswordRequestDTOInterface $requestDTO): RequestResultInterface
    {
        $securityResponse = $this->securityService->callSecurityRoute('security_password_reset_email', [
            'email' => $requestDTO->getEmail(),
        ]);

        if ((int)$securityResponse['code'] === 200) {
            return SuccessOk200::create();
        }

        return BadRequest::create($securityResponse['error'] ?: 'Запрос на воосстановление не отправлен');
    }

    public function getSubscriberNameByIp(IpAddressDTOInterface $ipAddressDTO): SubscriberNameResultDTOInterface
    {
        $subscriber = $this->subscriberRepository->getSubscriberByIp($ipAddressDTO->getIpAddress());
        if (!$subscriber->getPk()) {
            return SubscriberNameResultDTO::create(IpNotFound::create());
        }

        return SubscriberNameResultDTO::create(SuccessOk200::create(), $subscriber->get('/name'));
    }

    public function getSubscriberNameByInvite(InviteRequestDTOInterface $inviteRequestDTO): SubscriberNameResultDTOInterface
    {
        $subscriber = $this->subscriberRepository->getSubscriberByInvite($inviteRequestDTO->getInviteCode());

        if (!$subscriber->getPk()) {
            return SubscriberNameResultDTO::create(Unauthorized::create('Инвайт код не найден или уже был использован.'));
        }

        return SubscriberNameResultDTO::create(SuccessOk200::create(), $subscriber->get('/name'));
    }

    public function registerByEmail(RegisterUserRequestDTOInterface $requestDTO): RequestResultInterface
    {
        if (empty($requestDTO->getLastName()) || empty($requestDTO->getFirstName())) {
            return BadRequest::create('Введите фамилию и имя');
        }

        $registerParams = [
            'name' => $requestDTO->getFirstName(),
            'surname' => $requestDTO->getLastName(),
            'patronymic' => $requestDTO->getPatronymic(),
            'email' => $requestDTO->getEmail(),
            'password' => $requestDTO->getPassword(),
            'password1' => $requestDTO->getPassword(),
            'confirm' => 1
        ];

        $securityResponse = $this->securityService->callSecurityRoute('security_signup', $registerParams);

        if (isset($securityResponse['status']) && (string)$securityResponse['status'] === 'error') {
            return $this->makeFailedAuthErrorResult($securityResponse['error'] ?: 'Регистрация не удалась', (int)$securityResponse['code']);
        }

        $this->userRepository->getOrCreateMobileUserByUserKey($this->getUserKeyFromSecurityResponse($securityResponse));

        if ((int)$securityResponse['code'] === 200) {
            return SuccessOk200::create();
        }

        return $this->makeFailedAuthErrorResult($securityResponse['error'] ?: 'Регистрация не удалась', (int)$securityResponse['code']);
    }

    public function registerBySocial(RegisterUserRequestDTOInterface $requestDTO): UserAuthResultDTOInterface
    {
        if (empty($requestDTO->getInviteCode())
            && empty($this->subscriberRepository->getSubscriberByIp($requestDTO->getIpAddress())->getPk())
        ) {
            return new UserAuthResultDTO(
                MethodNotAllowedError::create('Для данного типа регистрации необходимо зайти в сеть вашей библиотеки'),
                UserAuthCardDTO::create()
            );
        }

        $registerSocialParams = [
            'account_type' => 'social',
            'email' => $requestDTO->getEmail(),
            'token' => $requestDTO->getSocialToken(),
            //'code' => $requestDTO->getSocialToken(),
            'firstName' => trim($requestDTO->getFirstName()),
            'lastName' => trim($requestDTO->getLastName()),
            'network' => $requestDTO->getSocialNetwork(),
            'securityExceptionThrow' => 0,
            'issuer' => Request::host(),
        ];

        if ($requestDTO->getSocialNetwork() === 'apple') {
            $registerSocialParams['account_type'] = 'apple';
            $registerSocialParams['mobile'] = 1;
        }

        $securityResponse = $this->securityService->callSecurityRoute('ebs_security_sign_in_account', $registerSocialParams);

        if (isset($securityResponse['status']) && (string)$securityResponse['status'] === 'error') {
            return $this->makeFailedAuthResponse($securityResponse['error'] ?: 'Регистрация не удалась', (int)$securityResponse['code']);
        }

        $this->userRepository->getOrCreateMobileUserByUserKey($this->getUserKeyFromSecurityResponse($securityResponse), $requestDTO->getInviteCode());

        if ((int)$securityResponse['code'] === 200) {
            $subscriber = $this->securityService->getSubscriber();
            $user = $securityResponse['jwt']['data']['payload']['user'];
            $userExpiredDate = $this->userRepository->getAuthenticatedUserExpiredDate();

            $this->userRepository->setUserByApp($user['id']);

            return new UserAuthResultDTO(
                SuccessOk200::create(),
                UserAuthCardDTO::create(
                    id: $user['id'],
                    xAuthToken: $this->securityService->getAccessToken(),
                    name: $user['surname'] . ' ' . $user['name'] . ' ' . $user['patronymic'],
                    email: $user['email'],
                    subscriber: $subscriber ? $subscriber->get('/name') : 'Пользователь не привязан к организации',
                    subscriptionEndDate: $userExpiredDate ? EbsCarbon::parse($userExpiredDate) : EbsCarbon::create(),
                ),
            );
        }

        return $this->makeFailedAuthResponse($securityResponse['error'] ?: 'Регистрация не удалась', (int)$securityResponse['code']);
    }

    public function deactivateUser(): RequestResultInterface
    {
        if ($this->securityService->checkIfUserInSubscribeReaderGroup()) {
            return NoAccessToResourceError::create('Такова нельзя делать пользователя');
        }

        if ($this->securityService->checkIfUserInGuestGroup()) {
            return NoAccessToResourceError::create('Мы не можем нарушить закон гостеприимства и удалить гостя.');
        }

        $this->mailService->sendCurrentUserDeactivationRequestEmail();

        return SuccessOk200::create();
    }

    private function logIn(array $loginParams): UserAuthResultDTOInterface
    {
        try {
            $securityResponse = $this->securityService->callSecurityRoute('ebs_security_sign_in_account', $loginParams);

            $securityStatusCode = (int)$securityResponse['code'];

            if ($securityStatusCode === 401) {
                $message = 'Данный аккаунт' . (isset($params['account_type']) && (string)$params['account_type'] === 'password' ? ' ' : ' социальной сети ') . 'не найден.';
                return new UserAuthResultDTO(AccountNotFoundError::create($message), UserAuthCardDTO::create());
            }

            if ($securityStatusCode === 200) {
                $this->userRepository->getOrCreateMobileUserByUserKey($this->getUserKeyFromSecurityResponse($securityResponse));

                $subscriber = $this->securityService->getSubscriber();
                $user = $securityResponse['jwt']['data']['payload']['user'];

                $this->userRepository->setUserByApp($user['id']);

                $userExpiredDate = $this->userRepository->getAuthenticatedUserExpiredDate();

                $this->securityService->getUser();

                return new UserAuthResultDTO(
                    SuccessOk200::create(),
                    UserAuthCardDTO::create(
                        id: $user['id'],
                        xAuthToken: $securityResponse['jwt']['access_token'],
                        name: $user['surname'] . ' ' . $user['name'] . ' ' . $user['patronymic'],
                        email: $user['email'],
                        subscriber: $subscriber ? $subscriber->get('/name') : 'Пользователь не привязан к организации',
                        subscriptionEndDate: $userExpiredDate ? EbsCarbon::parse($userExpiredDate) : EbsCarbon::create(),
                    ),
                );
            }

            return $this->makeFailedAuthResponse($securityResponse['error'] ?: 'Авторизация не удалась', (int)$securityResponse['code']);
        } catch (Security_User_NotFound $e) {
            return new UserAuthResultDTO(AccountNotFoundError::create(), UserAuthCardDTO::create());
        }
    }

    private function getUserKeyFromSecurityResponse(array $securityResponse): string
    {
        return isset($securityResponse['user_key']) ? $securityResponse['user_key'] : $securityResponse['data']['user_key'];
    }

    private function makeFailedAuthResponse(string $errorMessage, int $statusCode): UserAuthResultDTOInterface
    {
        return new UserAuthResultDTO(
            $this->makeFailedAuthErrorResult($errorMessage, $statusCode),
            UserAuthCardDTO::create()
        );
    }

    private function makeFailedAuthErrorResult(string $errorMessage, int $statusCode): RequestResultInterface
    {
        return CustomHttpResult::create($errorMessage, $statusCode);
    }
}

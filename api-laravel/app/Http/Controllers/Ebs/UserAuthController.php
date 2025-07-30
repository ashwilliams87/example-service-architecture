<?php

namespace App\Http\Controllers\Ebs;

use App\Http\Controllers\EbsController;
use App\Http\Requests\Auth\CheckInviteCodeRequest;
use App\Http\Requests\Auth\CheckIpRequest;
use App\Http\Requests\Auth\LoginBySocialRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RecoverPasswordRequest;
use App\Http\Requests\Auth\RegisterByEmailRequest;
use App\Http\Requests\Auth\RegisterBySocialRequest;
use Illuminate\Http\Response;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Contracts\Services\UserAuthServiceInterface;

class UserAuthController extends EbsController
{
    public function __construct(
        public UserAuthServiceInterface    $userService,
        public ApiResponseServiceInterface $apiResponseService,
    )
    {
    }

    public function logIn(LoginRequest $request): Response
    {
        return $this->apiResponseService->makeObjectResponseByMobileResult(
            $this->userService->logInByEmail($request->toDTO())
        );
    }

    public function logInBySocial(LoginBySocialRequest $request): Response
    {
        return $this->apiResponseService->makeObjectResponseByMobileResult(
            $this->userService->logInBySocial($request->toDTO())
        );
    }

    public function logOut(): Response
    {
        return $this->apiResponseService->makeEmptyResponseByResult($this->userService->logOut());
    }

    public function registerByEmail(RegisterByEmailRequest $request): Response
    {
        return $this->apiResponseService->makeEmptyResponseByResult(
            $this->userService->registerByEmail($request->toDTO())
        );
    }

    public function registerBySocial(RegisterBySocialRequest $request): Response
    {
        return $this->apiResponseService->makeObjectResponseByMobileResult(
            $this->userService->registerBySocial($request->toDTO())
        );
    }

    public function recoverPassword(RecoverPasswordRequest $request): Response
    {
        return $this->apiResponseService->makeEmptyResponseByResult($this->userService->recoverPassword($request->toDTO()));
    }

    public function checkIp(CheckIpRequest $request): Response
    {
        return $this->apiResponseService->makeObjectResponseByMobileResult(
            $this->userService->getSubscriberNameByIp($request->toDTO())
        );
    }

    public function checkInvite(CheckInviteCodeRequest $request): Response
    {
        return $this->apiResponseService->makeObjectResponseByMobileResult(
            $this->userService->getSubscriberNameByInvite($request->toDTO())
        );
    }

    public function deactivateUser(): Response
    {
        return $this->apiResponseService->makeEmptyResponseByResult(
            $this->userService->deactivateUser()
        );
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\DataTypes\RequestResult\Error\Unauthorized;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAuthenticated
{

    public function __construct(
        protected SecurityServiceInterface $securityService,
        protected ApiResponseServiceInterface $apiResponseService
    )
    {

    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->securityService->isAuth()) {
            return $this->apiResponseService->makeUnauthorizedErrorResponse(Unauthorized::create());
        }

        return $next($request);
    }
}

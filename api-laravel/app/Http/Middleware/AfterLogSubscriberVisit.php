<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lan\Contracts\Services\UserActivityLogServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class AfterLogSubscriberVisit
{
    public function __construct(
        private UserActivityLogServiceInterface $userActivityLogService,
    )
    {

    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $this->userActivityLogService->logSubscriberVisit();
        return $response;
    }
}

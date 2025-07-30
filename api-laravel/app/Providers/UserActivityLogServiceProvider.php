<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\UserActivityLogRepositoryInterface;
use Lan\Contracts\Services\UserActivityLogServiceInterface;
use Lan\Repositories\UserActivityLogRepository;
use Lan\Services\UserActivityLogService;

class UserActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserActivityLogServiceInterface::class, UserActivityLogService::class);

        $this->app->bind(UserActivityLogRepositoryInterface::class, UserActivityLogRepository::class);
    }

    public function boot(): void
    {

    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\InviteRepositoryInterface;
use Lan\Contracts\Repositories\SubscriberRepositoryInterface;
use Lan\Contracts\Repositories\UserRepositoryInterface;
use Lan\Contracts\Services\UserAuthServiceInterface;
use Lan\Repositories\InviteRepository;
use Lan\Repositories\SubscriberRepository;
use Lan\Repositories\UserRepository;
use Lan\Services\UserAuthService;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserAuthServiceInterface::class, UserAuthService::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SubscriberRepositoryInterface::class, SubscriberRepository::class);

        $this->app->bind(InviteRepositoryInterface::class, InviteRepository::class);
    }

    public function boot(): void
    {

    }
}

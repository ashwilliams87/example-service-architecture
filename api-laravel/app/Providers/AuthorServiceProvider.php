<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\AuthorRepositoryInterface;
use Lan\Contracts\Services\AuthorServiceInterface;
use Lan\Repositories\AuthorRepository;
use Lan\Services\AuthorService;

class AuthorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthorServiceInterface::class, AuthorService::class);

        $this->app->bind(AuthorRepositoryInterface::class, AuthorRepository::class);
    }

    public function boot(): void
    {

    }
}

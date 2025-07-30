<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\SyncRepositoryInterface;
use Lan\Contracts\Services\SyncServiceInterface;
use Lan\Repositories\SyncRepository;
use Lan\Services\SyncService;

class SyncServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SyncServiceInterface::class, SyncService::class);

        $this->app->bind(SyncRepositoryInterface::class, SyncRepository::class);
    }

    public function boot(): void
    {

    }
}

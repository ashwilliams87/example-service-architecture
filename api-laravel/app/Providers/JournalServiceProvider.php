<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\JournalRepositoryInterface;
use Lan\Contracts\Services\JournalServiceInterface;
use Lan\Repositories\JournalRepository;
use Lan\Services\JournalService;

class JournalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(JournalServiceInterface::class, JournalService::class);

        $this->app->bind(JournalRepositoryInterface::class, JournalRepository::class);
    }

    public function boot(): void
    {

    }
}

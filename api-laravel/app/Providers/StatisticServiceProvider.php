<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\StatisticRepositoryInterface;
use Lan\Contracts\Services\StatisticServiceInterface;
use Lan\Repositories\StatisticRepository;
use Lan\Services\StatisticService;

class StatisticServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StatisticServiceInterface::class, StatisticService::class);

        $this->app->bind(StatisticRepositoryInterface::class, StatisticRepository::class);
    }

    public function boot(): void
    {

    }
}

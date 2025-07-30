<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\SearchRepositoryInterface;
use Lan\Contracts\Services\SearchServiceInterface;
use Lan\Repositories\SearchRepository;
use Lan\Services\SearchService;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SearchServiceInterface::class, SearchService::class);

        $this->app->bind(SearchRepositoryInterface::class, SearchRepository::class);

    }

    public function boot(): void
    {

    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\PublisherRepositoryInterface;
use Lan\Contracts\Services\PublisherServiceInterface;
use Lan\Repositories\PublisherRepository;
use Lan\Services\PublisherService;

class PublisherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PublisherServiceInterface::class, PublisherService::class);

        $this->app->bind(PublisherRepositoryInterface::class, PublisherRepository::class);
    }

    public function boot(): void
    {

    }
}

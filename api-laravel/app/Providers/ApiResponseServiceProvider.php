<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\ApiResponseBuilderInterface;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Services\Response\ApiResponseBuilder;
use Lan\Services\Response\ApiResponseService;

class ApiResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ApiResponseServiceInterface::class, ApiResponseService::class);

        $this->app->bind(ApiResponseBuilderInterface::class, ApiResponseBuilder::class);
    }

    public function boot(): void
    {

    }
}

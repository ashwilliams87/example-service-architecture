<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\CategoryRepositoryInterface;
use Lan\Contracts\Services\CategoryServiceInterface;
use Lan\Repositories\CategoryRepository;
use Lan\Services\CategoryService;

class CategoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
    }

    public function boot(): void
    {

    }
}

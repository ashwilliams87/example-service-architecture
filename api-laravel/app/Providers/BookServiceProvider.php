<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Repositories\BookRepositoryInterface;
use Lan\Contracts\Services\BookServiceInterface;
use Lan\Repositories\BookRepository;
use Lan\Services\BookService;

class BookServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookServiceInterface::class, BookService::class);

        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);

    }

    public function boot(): void
    {

    }
}

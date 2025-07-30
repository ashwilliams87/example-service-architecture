<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Services\MailServiceInterface;
use Lan\Services\MailService;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MailServiceInterface::class, MailService::class);
    }

    public function boot(): void
    {

    }
}

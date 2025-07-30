<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lan\Contracts\Services\Security\CryptServiceInterface;
use Lan\Contracts\Services\Security\DocumentCryptServiceInterface;
use Lan\Contracts\Services\Security\DownloadProtectorServiceInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\Services\Security\CryptService;
use Lan\Services\Security\DocumentCryptService;
use Lan\Services\Security\DownloadProtectService;
use Lan\Services\Security\SecurityService;

class SecurityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SecurityServiceInterface::class, SecurityService::class);

        $this->app->singleton(DownloadProtectorServiceInterface::class, DownloadProtectService::class);
        $this->app->singleton(DocumentCryptServiceInterface::class, DocumentCryptService::class);
        $this->app->singleton(CryptServiceInterface::class, CryptService::class);
    }

    public function boot(): void
    {

    }
}

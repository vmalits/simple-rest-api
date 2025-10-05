<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\IdentityService;
use App\Services\IdentityServiceContract;
use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IdentityServiceContract::class, IdentityService::class);
    }

    public function boot(): void
    {
    }
}

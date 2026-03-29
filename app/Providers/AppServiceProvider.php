<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\ClaudeService::class);
        $this->app->singleton(\App\Services\OnvioService::class);
        $this->app->singleton(\App\Services\AuditService::class);
        $this->app->singleton(\App\Services\NoticiaService::class);
        $this->app->singleton(\App\Services\ExtratoParserService::class);
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\internalinspection;
use App\Observers\InternalinspectionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        internalinspection::observe(InternalinspectionObserver::class);
    }
}

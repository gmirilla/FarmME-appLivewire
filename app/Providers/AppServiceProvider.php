<?php

namespace App\Providers;

use App\Models\internalinspection;
use App\Observers\InternalinspectionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        internalinspection::observe(InternalinspectionObserver::class);
    }
}

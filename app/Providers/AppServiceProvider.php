<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        Route::bind('client', function ($value) {
            return \App\Models\Client::where('company_id', app('scope.company_id'))
                ->findOrFail($value);
        });
        Route::bind('quote', function ($value) {
            return \App\Models\Quote::where('company_id', app('scope.company_id'))
                ->findOrFail($value);
        });
    }
}

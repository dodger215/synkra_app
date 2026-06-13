<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->tenant_id) {
                // Share active tenant services with all views for navigation rendering
                $activeServices = \Illuminate\Support\Facades\Auth::user()->tenant->services()
                                    ->where('is_active', true)
                                    ->pluck('config', 'service_name')
                                    ->toArray();
                
                $view->with('activeServices', $activeServices);
            }
        });
    }
}

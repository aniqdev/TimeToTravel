<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Paginator::useBootstrap();

         Collection::macro('nullToZero', function () {
            return $this->map(function ($route) {
                if (is_null($route->is_favorite)) {
                    $route->is_favorite = 0;
                }
                if (is_null($route->is_viewed)) {
                    $route->is_viewed = 0;
                }
                if (is_null($route->is_downloaded)) {
                    $route->is_downloaded = 0;
                }
                return $route;
            });
        });
    }
}

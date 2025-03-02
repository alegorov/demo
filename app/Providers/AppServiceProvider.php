<?php

namespace App\Providers;

use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    public $singletons = [
        GoogleSheetService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->register(CrudProvider::class);

        Route::macro('crudResource', function ($name, $controllerClass) {
            Route::prefix($name)->group(fn($router) => app()->make($controllerClass)->routes($router));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
    }
}

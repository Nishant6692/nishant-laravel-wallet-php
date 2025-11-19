<?php

namespace Nishant\Wallet;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/wallet.php',
            'wallet'
        );

        $this->app->singleton(WalletService::class, function ($app) {
            return new WalletService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/wallet.php' => config_path('wallet.php'),
        ], 'wallet-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'wallet-migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerRoutes();
    }

    /**
     * Register package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * Get route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'prefix' => config('wallet.route_prefix', 'api/wallet'),
            'middleware' => config('wallet.middleware', ['api']),
        ];
    }
}


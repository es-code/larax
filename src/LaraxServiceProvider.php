<?php

namespace Escode\Larax;
use Escode\Larax\App\Http\Middleware\AuthLaraxApi;
use Illuminate\Routing\Router;


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class LaraxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'larax'
        );
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views','larax');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->publishes([
            __DIR__.'/config.php' => config_path('larax.php')
        ], 'config');

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('AuthLaraxApi', AuthLaraxApi::class);

        //check if want to detect user id
        if(config('larax.detect_user')===true){
            $kernel = $this->app->make(Kernel::class);
            $kernel->pushMiddleware(\App\Http\Middleware\EncryptCookies::class);
            $kernel->pushMiddleware(\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class);
            $kernel->pushMiddleware(\Illuminate\Session\Middleware\StartSession::class);
        }
    }
}

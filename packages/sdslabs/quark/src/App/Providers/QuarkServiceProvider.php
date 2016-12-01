<?php

namespace SDSLabs\Quark\App\Providers;

use SDSLabs\Quark\App\Auth\FalconGuard;
use SDSLabs\Quark\App\Http\Middleware\Authenticate;
use SDSLabs\Quark\App\Http\Middleware\Developer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;

class QuarkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->setupRoutes($router);
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations/');

        Auth::extend('falcon', function($app, $name, array $config) {
            return new FalconGuard;
        });

        $router->middleWare('auth', Authenticate::class);
        $router->middleWare('developer', Developer::class);
    }

    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'SDSLabs\Quark\App\Http\Controllers'], function($router)
        {
            require __DIR__.'/../Http/routes.php';
        });
    }

    public function register()
    {
    }
}
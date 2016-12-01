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

    protected $router;


    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function boot()
    {
        $this->setupRoutes();
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations/');

        Auth::extend('falcon', function($app, $name, array $config) {
            return new FalconGuard;
        });
    }

    public function setupRoutes(Router $router)
    {
        $this->router->group([
            'namespace' => 'SDSLabs\Quark\App\Http\Controllers'
        ], function($router) {
            require __DIR__.'/../Http/routes.php';
        })->middleWare('auth', Authenticate::class)->middleWare('developer', Developer::class);
    }

    public function register()
    {
    }
}
<?php

namespace SDSLabs\Quark;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;


class QuarkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupRoutes($this->app->router);
        $this->loadMigrationsFrom(__DIR__.'/database/migrations/');
    }

    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'SDSLabs\Quark\App\Http\Controllers'], function($router)
        {
            require __DIR__.'/App/Http/routes.php';
        });
    }

    public function register()
    {
    }
}
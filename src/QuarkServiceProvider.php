<?php

namespace SDSLabs\Quark;

use SDSLabs\Quark\App\Auth\FalconGuard;
use SDSLabs\Quark\App\Http\Middleware\Authenticate;
use SDSLabs\Quark\App\Http\Middleware\SubstituteBindings;
use SDSLabs\Quark\App\Http\Middleware\Developer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
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

		$this->loadMigrationsFrom(__DIR__.'/database/migrations/');

		Auth::extend('falcon', function($app, $name, array $config) {
			return App::make(FalconGuard::class);
		});
	}

	public function setupRoutes(Router $router)
	{
		$router->prependMiddlewareToGroup('web',SubstituteBindings::class);

		$router->middleWare('auth', Authenticate::class);
		$router->middleWare('developer', Developer::class);
		$router->middleWare('developer_check', DeveloperCheck::class);

		$router->group([
			'namespace' => 'SDSLabs\Quark\App\Http\Controllers',
			'middleware' => 'web'
		], function($router) {
			require __DIR__.'/App/Http/routes.php';
		});
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/config/auth.php', 'auth');
	}

}

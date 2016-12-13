<?php

namespace SDSLabs\Quark\App\Providers;

use SDSLabs\Quark\App\Auth\FalconGuard;
use SDSLabs\Quark\App\Http\Middleware\Authenticate;
use SDSLabs\Quark\App\Http\Middleware\Developer;
use SDSLabs\Quark\App\Validators\CustomValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


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

		$this->addValidationRules();
	}

	public function setupRoutes(Router $router)
	{
		$router->middleWare('auth', Authenticate::class);
		$router->middleWare('developer', Developer::class);
		$router->middleWare('developer_check', DeveloperCheck::class);

		$router->group([
			'namespace' => 'SDSLabs\Quark\App\Http\Controllers',
			'middleware' => 'web'
		], function($router) {
			require __DIR__.'/../Http/routes.php';
		});
	}

	public function addValidationRules()
	{
		Validator::resolver(function($translator, $data, $rules, $messages = array(), $customAttributes = array()) {
			return new CustomValidator($translator, $data, $rules, $messages, $customAttributes);
		});
	}

	public function register()
	{
	}

}

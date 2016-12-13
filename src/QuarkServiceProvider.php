<?php

namespace SDSLabs\Quark;

use Illuminate\Support\ServiceProvider;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use RegexIterator;
use RecursiveRegexIterator;

class QuarkServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');
		$this->loadRoutesFrom(__DIR__.'/App/Http/routes.php');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->register_models();
	}

	protected function register_models() {
		$root = __DIR__."/App/Models";
		$models = $this->fetch_classes($root);
	}

	protected function fetch_classes($root) {
		$dir_iter = new RecursiveDirectoryIterator($root);
		$iter = new RecursiveIteratorIterator($dir_iter);
		$reg_iter = new RegexIterator($iter,
			"/^" . str_replace('/', '\/', $root) . "(.+)\.php$/i",
		   	RecursiveRegexIterator::GET_MATCH);

		$classes = [];
		foreach ($reg_iter as $path => $matches)  {
			$classes[] = str_replace("/", "\\", $matches[1]);
		}

		return $classes;
	}
}

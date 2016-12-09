<?php

namespace SDSLabs\Quark\App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;


class DeveloperCheck
{
	/**
	 * The authentication guard factory instance.
	 *
	 * @var \Illuminate\Contracts\Auth\Factory
	 */
	protected $auth;

	/**
	 * Create a new middleware instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Factory  $auth
	 * @return void
	 */
	public function __construct(Auth $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = 'falcon')
	{

		if(config('auth.developer_only'))
		{
			if (!$this->auth->guard($guard)->user())
				$this->auth->guard($guard)->login();

			if (!$this->auth->guard($guard)->user()->isDeveloper())
				abort(404, 'Developers only');
		}

		return $next($request);

	}
}

<?php

namespace SDSLabs\Quark\App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;


class Authenticate
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

		if ($this->auth->guard($guard)->guest()) {
			return $this->auth->guard($guard)->login();
		}

		return $next($request);
	}
}

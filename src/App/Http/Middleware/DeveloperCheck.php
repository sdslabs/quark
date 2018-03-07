<?php

namespace SDSLabs\Quark\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
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
		if(App::environment('testing'))
		{
			return app(FalconAuthenticate::class)->handle($request, function ($request) use ($next, $guard) {

				if (!$this->isInOrganization($guard))
					abort(403, 'Member of Organizations Only');

				return $next($request);

			}, $guard);
		}

		return $next($request);

	}


	private function isInOrganization($guard)
	{
		$organizations = config('auth.organizations_allowed');

		foreach ($this->auth->guard($guard)->falconUser()["organizations"] as $organization) {
			if(in_array($organization, $organizations))
				return true;
		}

		return false;
	}
}

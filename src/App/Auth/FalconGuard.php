<?php

namespace SDSLabs\Quark\App\Auth;

use SDSLabs\FalconClient\API;
use SDSLabs\Quark\App\Models\User;

use Illuminate\Contracts\Auth\Guard;

class FalconGuard implements Guard
{
	use \Illuminate\Auth\GuardHelpers;

	/*
	 * The request instance.
	 *
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/*
	 * The FalconClient API instance
	 *
	 * @var \SDSLabs\Falcon\API;
	 */
	protected $api;

	protected $team;

	/*
	 * Create a new authentication guard.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request  $request
	 * @return void
	 */
	public function __construct()
	{
		$this->api = new API(config('auth.falcon'));
	}

	/*
	 * Get the currently authenticated user.
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function user()
	{
		// If we've already retrieved the user for the current request we can just
		// return it back immediately. We do not want to fetch the user data on
		// every call to this method because that would be tremendously slow.
		if (!is_null($this->user)) {
			if($this->user)
				return $this->user;

			return null;
		}

		$result = $this->api->get_logged_in_user();

		if($result === null) {
			$this->user = false;
			return null;
		}


		$user = User::where('user_id', $result['id'])->first();

		if($user === null)
		{
			// New user
			$user = new User;

			$user->user_id = $result['id'];
			$user->provider = 'falcon';
			$user->credentials = $result['password'];
			$user->username = $result['username'];
			$user->fullname = $result['name'];
			$user->email = $result['email'];
			$user->image = $result['image_url'];

			$user->save();
		}
		else if($user->image != $result['image_url']) {
			$user->image = $result['image_url'];
			$user->save();
		}

		$this->user = $user;

		return $user;
	}

	/**
	 * Just to maintain the contract with Gurad abstract class
	 *
	 * @throws Exception
	 */
	public function validate(array $credentials = [])
	{
		throw "Dude you can't authenticate like this";
	}

	/**
	 * If the user is logged in it return the user, else
	 * it redirects the user ro login page
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function userOrLogin()
	{
		$user = $this->user();

		if($user === null)
			return $this->login();

		return $user;
	}

	/**
	 * Redirects the user to login page
	 */
	public function login()
	{
		$this->api->login();
	}
}

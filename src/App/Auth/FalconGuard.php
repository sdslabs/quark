<?php

namespace SDSLabs\Quark\App\Auth;

use SDSLabs\FalconClient\API;
use SDSLabs\Quark\App\Models\User;

use Illuminate\Support\Facades\App;
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

	/*
	 * The User Array returned by FalconClient API
	 *
	 * @array;
	 */
	protected $falcon_user;

	/*
	 * The Quark User obtained by mapping $falcon_user->id to $user->user_id
	 *
	 * @var \SDSLabs\Quark\App\Models\User;
	 */
	protected $user;

	/*
	 * Create a new authentication guard.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request  $request
	 * @return void
	 */
	public function __construct(User $user_model)
	{
		$this->user_model = $user_model;
		$this->user = null;
		$this->falcon_user = null;
		$this->api = new API(config('auth.falcon'));
	}

	public function falcon_user() {
		// We have already retrieved falcon user.
		// We do not want to fetch the user data on every call.
		// The method is tremendously slow.
		if (!is_null($this->falcon_user)) {
			return $this->falcon_user;
		}

		// The function falcon_user is called for the first time
		// Fetch the logged in user details from FalconClient.
		$result = $this->api->get_logged_in_user();

		// Set non-null falcon_user so that next time the function returns instantly.
		if ($result === null) {
			$this->falcon_user = false;
		}
		else {
			$this->falcon_user = $result;
		}

		return $this->falcon_user;

	}

	/*
	 * Get the currently authenticated user.
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function user()
	{
		if (!is_null($this->user)) {
			if ($this->user !== false) return $this->user;
			return null;
		}

		if ($this->falcon_user === false) {
			$this->user = false;
			return null;
		}

		$user = $this->user_model->where('user_id', $this->falcon_user['id'])->first();

		if ($user === null) {
			$this->user = false;
			return null;
		}

		$this->user = $user;

		return $this->user;
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

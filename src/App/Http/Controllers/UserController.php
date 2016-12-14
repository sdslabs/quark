<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

	public function __construct(User $users)
	{
		$this->users = $users;
		$this->middleware('auth')->except(['index', 'show']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		// Return practice leaderboard
		$users = $this->users->where('score', '>', 0)->orderBy('score', 'desc')->orderBy('score_updated_at', 'asc')->paginate(30);
		return $users;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user)
	{
		$user->load('teams', 'submissions.problems', 'problems_created');
		if(!is_null(Auth::user()))
		{
			if ($user->username === Auth::user()->username || Auth::user()->isDeveloper())
			{
				$user->makeVisible('email');
				$user->load('owned_teams', 'invites.team');
				// TODO: Group by status and hide the status
			}

			if (Auth::user()->isDeveloper())
				$user->makeVisible('role');
		}

		return $user;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function edit($name)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, User $user)
	{
		$this->validate($request, [
			'username' => 'bail|alpha_dash|unique:users,username,'.$user->id.',id',
			'fullname' => 'alpha'
		]);

		if ($user->username !== Auth::user()->username && !Auth::user()->isDeveloper())
			abort(403, "You don't have the permission to update this user.");

		$user->update($request->all());

		return $user;
	}

}

<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Role;

class UserController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth')->except(['index', 'show']);
	}

	public static function findByName($name)
	{
		return User::where("username", $name);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		// Return practice leaderboard
		$users = User::all();
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
		$user->load('teams', 'submissions', 'problems_created');
		if(!is_null(Auth::user()))
		{
			if ($user->username === Auth::user()->username || Auth::user()->isDeveloper())
			{
				$user->makeVisible('email');
				$user->load('owned_teams', 'team_invites');

				$user->team_invites->mapWithKeys(function($item) {
					$item->makeHidden('pivot');
					$item->status = $item->pivot->status;
					$item->token = $item->pivot->token;
					$item->created_at = $item->pivot->created_at;
					$item->updated_at = $item->pivot->updated_at;
					return $item;
				});
				$user['invites'] = $user->team_invites->groupBy(function($item, $key) {
					if($item['status'] == 1)
						return 'received';
					elseif($item['status'] == 2)
						return 'sent';
					else
						return 'other';
				});
				$user['invites']->forget('other');
				$user->makeHidden('team_invites');
				// TODO: Find a better way for it and hide status from invites!!
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

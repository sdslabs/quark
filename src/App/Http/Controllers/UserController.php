<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

	public function __construct(User $users)
	{
		$this->users = $users;
		$this->middleware('auth')->except(['index', 'show', 'store']);
		$this->middleware('falcon_auth')->only('store');
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
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{
		$this->validate($request, [
			'username' => 'bail|required|alpha_dash|between:3,30|unique:users,username',
			'fullname' => 'bail|required|regex:/^[\pL\s\-]+$/u|between:3,30',
			'image' => 'bail|mimes:jpeg,jpg,png,gif|max:5120',
		]);

		$user = App::make(User::class, [$request->all()]);
		$user->user_id = Auth::falcon_user()['id'];
		$user->email = Auth::falcon_user()['email'];
		$user->provider = 'falcon';

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$image = $request->file('image');
			$ext = $image->getClientOriginalExtension();
			$user->image = $image->storeAs("user_profile", $user->username.".".$ext, "public");
		}

		$user->save();

		return $user;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user)
	{
		$user->load('teams', 'submissions.problem', 'problems_created');
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
			'username' => 'bail|required|alpha_dash|between:3,30|unique:users,username'.$user->id.',id',
			'fullname' => 'bail|required|regex:/^[\pL\s\-]+$/u|between:3,30',
			'image' => 'bail|mimes:jpeg,jpg,png,gif|max:5120',
		]);

		if ($user->username !== Auth::user()->username && !Auth::user()->isDeveloper())
			abort(403, "You don't have the permission to update this user.");

		$user->update($request->all());

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$image = $request->file('image');
			$ext = $image->getClientOriginalExtension();
			$user->image = $image->storeAs("user_profile", $user->username.".".$ext, "public");
		}

		$user->save();

		return $user;
	}

	public function showMe()
	{
		return $this->show(Auth::user());
	}

}

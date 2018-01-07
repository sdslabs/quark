<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

	public function __construct(User $users)
	{
		$this->users = $users;
		$this->middleware('auth')->except(['index', 'show', 'store', 'showFalconMe']);
		$this->middleware('falcon_auth')->only(['store', 'showFalconMe']);
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
	public function store(Request $request)
	{
		if (!is_null(Auth::user())) {
			abort(409, "Already logged in.");
		}

		$this->validate($request, [
			'username' => 'bail|required|alpha_dash|between:3,30|unique:users,username',
			'fullname' => 'bail|required|regex:/^[\pL\s\-]+$/u|between:3,30',
			'image' => 'bail|mimes:jpeg,jpg,png,gif|max:5120',
		]);

		// No idea why it doesn't work!
		// $user = App::make(User::class, [$request->all()]);

		$user = App::make(User::class);
		$user->username = $request->username;
		$user->fullname = $request->fullname;
		$user->user_id = Auth::falconUser()['id'];
		$user->email = Auth::falconUser()['email'];
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
	public function show(Request $request, User $user)
	{
		if(!is_null(Auth::user()))
		{
			if ($user->username === Auth::user()->username || Auth::user()->isDeveloper())
			{
				$user->makeVisible('email');
				// TODO: Group by status and hide the status in invites
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
			'username' => 'bail|required|alpha_dash|between:3,30|unique:users,username,'.$user->id.',id',
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

	public function showFalconMe()
	{
		$falcon_user = Auth::falconUser();
		return [
			"username" => $falcon_user['username'],
			"fullname" => $falcon_user['name'],
			"email" => $falcon_user['email'],
			"image" => $falcon_user['image_url'],
		];
	}

	public function showMe(Request $request)
	{
		return $this->show($request, Auth::user());
	}

	public function showCompetitionTeam(Competition $competition)
	{
		$teams = Auth::user()->teams();
		$competition_team = $teams->where('competition_id',$competition->id)->firstOrFail();
		$competition_team->load('members', 'invites.user', 'owner');
		return $competition_team;
	}

	public function showInvites(Competition $competition)
	{
		$invites = Auth::user()->invites()->join('teams', 'user_team_invites.team_id', '=', 'teams.id');
		$competition_invites = $invites->where('teams.competition_id',$competition->id)->get();
		foreach($competition_invites as &$team) {
			$team->load('team.owner');
		}
		return $competition_invites;
	}
}

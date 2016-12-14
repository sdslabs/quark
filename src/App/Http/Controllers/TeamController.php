<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Team;
use SDSLabs\Quark\App\Models\Competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TeamController extends Controller
{

	public function __construct(Team $teams)
	{
		$this->teams = $teams;
		$this->middleware('auth')->except(['index', 'show']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Competition $competition)
	{
		$teams = $competition->teams()->get();
		return $teams;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Competition $competition)
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Competition $competition)
	{
		$this->validate($request, [
			'name' => 'bail|required|alpha_dash'
		]);

		if ($competition->status === 'Finished')
			abort(422, "Competition has already ended.");

		$user = Auth::user();

		if ($user->isInCompetition($competition->id))
			abort(422, "You are already participating in this competition");

		if ($competition->teams()->where('name', $request->name)->count() > 0)
			abort(422, "Team name is already taken.");

		$team = app()->make(Team::class, [$request->all()]);
		$team->owner()->associate($user);
		$saved = $competition->addTeam($team);

		if ($saved)
			$team->addMember($user);

		return $team;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Competition $competition, $team_name)
	{
		$team = $competition->teams()->where('name', $team_name)->firstOrFail();
		$team->load('submissions.problem', 'members');

		$user = Auth::user();
		if (!is_null($user) && $team->hasMember($user))
		{
			$team->load('owner', 'invites.user');
			// TODO: Group by status and hide the status
		}

		return $team;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Competition $competition, Team $team)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Competition $competition, $team_name)
	{
		$this->validate($request, [
			'name' => 'alpha_dash'
		]);

		if ($competition->status === 'Finished')
			abort(422, "Competition has already ended.");

		$team = $competition->teams()->where('name', $team_name)->firstOrFail();

		if ($team->owner->id !== Auth::user()->id && !Auth::user()->isDeveloper())
			abort(403, "Only owner can update the team details.");

		if ($request->has('name') &&
			$competition->teams()
				->where('name', $request->name)
				->where('id', '!=', $team->id)->count() > 0)
					abort(422, "A team with given name already exists.");

		$team->update($request->all());

		return $team;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Competition $competition, $team_name)
	{
		if($competition->status !== 'Future')
			abort(422, "The competition has already started.");

		$team = $competition->teams()->where('name', $team_name)->firstOrFail();

		if($team->owner->id !== Auth::user()->id && !Auth::user()->isDeveloper())
			abort(403, "Only owner can delete the team.");

		$team->delete();

		return;
	}
}

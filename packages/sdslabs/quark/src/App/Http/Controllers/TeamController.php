<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Team;
use SDSLabs\Quark\App\Models\Competition;

class TeamController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth')->except(['index', 'show']);
	}

	public static function findByName($name)
	{
		return Team::where("name", $name);
	}

	public static function findByCompetitionId($competition_id)
	{
		$user = Auth::user();
		return $user->teams()->where('competition_id', $competition_id);
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

		if (TeamController::findByName($request->name)->where('competition_id', $competition->id)->count() > 0)
			abort(422, "Team name is already taken.");

		$team = new Team($request->all());
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
		$team = TeamController::findByName($team_name)->where('competition_id', $competition->id)->firstOrFail();
		$team->load('submissions.problem');

		$user = Auth::user();
		if (!is_null($user) && $team->hasMember($user))
		{
			$team->load('owner', 'user_invites', 'members');

			$team->user_invites->mapWithKeys(function($item) {
				$item->makeHidden('pivot');
				$item->status = $item->pivot->status;
				$item->token = $item->pivot->token;
				$item->created_at = $item->pivot->created_at;
				$item->updated_at = $item->pivot->updated_at;
				return $item;
			});
			$team['invites'] = $team->user_invites->groupBy(function($item, $key) {
				if($item['status'] == 1)
					return 'sent';
				elseif($item['status'] == 2)
					return 'received';
				else
					return 'other';
			});
			$team['invites']->forget('other');
			$team->makeHidden('user_invites');
			// TODO: Find an elegant way to do that and remove status from invites.
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

		$team = TeamController::findByName($team_name)->where('competition_id', $competition->id)->firstOrFail();

		if ($team->owner->id !== Auth::user()->id && !Auth::user()->isDeveloper())
			abort(403, "Only owner can update the team details.");

		if ($request->has('name') &&
			TeamController::findByName($request->name)
				->where('competition_id', $competition->id)
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

		$team = TeamController::findByName($team_name)->where('competition_id', $competition->id)->firstOrFail();

		if($team->owner->id !== Auth::user()->id && !Auth::user()->isDeveloper())
			abort(403, "Only owner can delete the team.");

		$team->delete();

		return;
	}
}

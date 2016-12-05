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

    public static function findByCompetition(Competition $competition)
    {
        $user = Auth::user();
        return $user->all_teams()->where('competition_id', $competition->id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Competition $competition)
    {
        $teams = $competition->teams()->with('members')->get();
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

        if($competition->status === 'Finished')
            return "Competition has already ended.";

        $user = Auth::user();

        $user_team = TeamController::findByCompetition($competition)->first();
        if(!is_null($user_team))
            return "You have already joined the team \"{$user_team->name}\" for this competition";

        $team = TeamController::findByName($request->name)->where('competition_id', $competition->id)->first();
        if(!is_null($team))
        	return "Team name is already taken.";

        $team = new Team($request->name());
        $team->owner()->associate($user);
        $saved = $competition->addTeam($team);

        if($saved)
            $team->addMember($user);

        return;
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
        $team->load('competition_logs.problem');

        $user = Auth::user();
        if(!is_null($user) && $team->hasMember($user))
            $team->load('owner', 'invites_sent', 'invites_received', 'members');

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

        if($competition->status === 'Finished')
            abort(403, "Competition has already ended.");

        $team = TeamController::findByName($team_name)->where('competition_id', $competition->id)->firstOrFail();

        if($team->owner->id !== Auth::user()->id)
            abort(403, "Only owner can update the team details.");

        if ($request->has('name') &&
            TeamController::findByName($request->name)
            	->where('competition_id', $competition->id)
        		->where('id', '!=', $team->id)->count() > 0)
        			abort(403, "A team with given name already exists.");

        $team->update($request->all());

        return;
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
            abort(403, "The competition has already started.");

        $team = TeamController::findByName($team_name)->where('competition_id', $competition->id)->firstOrFail();

        if($team->owner->id !== Auth::user()->id)
            abort(403, "Only owner can delete tht team.");

        $team->delete();
    }
}

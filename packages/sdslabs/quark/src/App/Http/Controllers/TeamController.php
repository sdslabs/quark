<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Team;

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

    public static function findByCompetition($comp)
    {
        $user = Auth::user();
        return $user->all_teams()->where('competition_id', $comp->id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($comp_name)
    {
        $comp = CompetitionController::findByName($comp_name)->first();
        $teams = $comp->teams()->with('members');
        return $teams->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($comp_name)
    {
        $form = "
                <form method='POST' action='".route('competitions.teams.store', $comp_name)."'>
                    <input type='text' name='name'></input>
                </form>
                ";
        return $form;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $comp_name)
    {
    	$this->validate($request, [
    		'name' => 'bail|required|alpha_dash'
    	]);

        $comp = CompetitionController::findByName($comp_name)->first();
        if($comp->status === 'Finished')
            return "Competition has already ended.";

        $user = Auth::user();

        $user_team = TeamController::findByCompetition($comp)->first();
        if(!is_null($user_team))
            return "You have already joined the team \"{$user_team->name}\" for this competition";

        $team = TeamController::findByName($request->name)->first();
        if(!is_null($team))
        	return "Team name is already taken.";

        $team = new Team($request->name());
        $team->owner()->associate($user);
        $saved = $comp->addTeam($team);

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
    public function show($comp_name, $team_name)
    {
        $comp = CompetitionController::findByName($comp_name)->first();
        $team = TeamController::findByName($team_name)->where('competition_id', $comp->id);
        if(is_null($team->first())) return;

        $team = $team->with('competition_logs.problem');
        $user = Auth::user();
        if(!is_null($user) && $team->first()->hasMember($user))
        {
            $team = $team->with('owner', 'invites_sent', 'invites_received', 'members');
        }

        return $team->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($comp_name, $team_name)
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
    public function update(Request $request, $comp_name, $team_name)
    {
    	$this->validate($request, [
    		'name' => 'alpha_dash'
    	]);

        $comp = CompetitionController::findByName($comp_name)->first();
        if($comp->status === 'Finished')
            return "Competition has already ended.";

        $team = TeamController::findByName($team_name)->where('competition_id', $comp->id)->first();
        if(is_null($team)) return;

        if($team->owner->id !== Auth::user()->id)
            return "Only owner can update the team details";

        if($request->has('name'))
        {
        	$existing_team = TeamController::findByName($request->name)->where('competition_id', $comp->id)
        		->where('id', '<>', $team->id)->count();
        	if(!is_null($existing_team))
        		return "A team with given name already exists";
        }

        $team->update($request->all());

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($comp_name, $team_name)
    {
        $comp = CompetitionController::findByName($comp_name)->first();
        if($comp->status === 'Running' || $comp->status === 'Finished')
            return "The competition is either over or running";

        $team = TeamController::findByName($team_name)->where('competition_id', $comp->id)->first();
        if(is_null($team)) return;

        if($team->owner->id !== Auth::user()->id)
            return "Only owner can delete tht team";

        $team->delete();
    }
}

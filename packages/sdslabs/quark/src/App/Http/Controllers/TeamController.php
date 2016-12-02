<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Team;

class TeamController extends Controller
{
    public static function findByName($name)
    {
        return Team::where("name", $name);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $comp_name)
    {
        //
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

        $user = Auth::user();
        $team = $team->with('competition_logs.problem');
        if(!is_null($user) && $team->first()->hasMember($user->id))
        {
            $team->with('owner', 'invites_sent', 'invites_received');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($comp_name, $team_name)
    {
        //
    }
}

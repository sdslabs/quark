<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Competition;
use SDSLabs\Quark\App\Helpers\Leaderboard;

class CompetitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('developer')->only(['create', 'store', 'edit', 'destroy']);
    }

    public function findByName($name)
    {
        return Competition::where("name", $name)->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$competitions = Competition::all()->groupBy('status');
        return $competitions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $comp = $this->findByName($name);
        $user = Auth::user();
        if(!is_null($user))
        {
            $comp->user = $user;
            $teams = $comp->teams();
            foreach ($teams as $team)
            {
                if(!is_null($team->members()->find($user->id)))
                {
                    $comp->team = $team;
                    break;
                }
            }
        }
        return $comp;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        //
    }

    public function showLeaderboard($name, Request $request)
    {
        $comp = $this->findByName($name);
        $limit = $request->input('limit');
        $limit = is_null($limit) ? 50 : $limit;
        $leaderboard = Leaderboard::competitionLeaderboard($comp, $limit);
        $leaderboard->setPath($comp->leaderboard.'?limit='.$limit);
        return $leaderboard;
    }
}

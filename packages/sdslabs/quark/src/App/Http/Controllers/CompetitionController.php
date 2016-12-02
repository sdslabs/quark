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
        // $this->middleware('developer')->only(['create', 'store', 'edit', 'destroy']);
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
        $comp = new Competition($request->all());
        $saved = $comp->save();
        return;
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
        if(is_null($comp)) return;
        $user = Auth::user();
        if(!is_null($user))
        {
            $comp->team = $user->teams()['all']->where('competition_id', $comp->id)->with('members')->first();
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
        $comp = $this->findByName($name);
        if(is_null($comp)) return;
        $comp->update($request->all());
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $comp = $this->findByName($name);
        if(is_null($comp)) return;
        $comp->delete();
        return;
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

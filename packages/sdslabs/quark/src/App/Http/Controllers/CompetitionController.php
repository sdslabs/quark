<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Competition;

class CompetitionController extends Controller
{

    /**
     * Applied developer Middleware to all routes which require developer access
     * 
     */

    public function __construct()
    {
        $this->middleware('developer')->only(['create', 'store', 'edit', 'destroy']);
    }
    /**
     * Find a competition by given name.
     * 
     * @param string $name
     * @return SDSLabs\Quark\App\Models\Competition
     */

    public static function findByName($name)
    {
        return Competition::where("name", $name);
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
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $comp = $this->findByName($name)->first();
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
     * @param  int  $name
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
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        $comp = $this->findByName($name)->first();
        if(is_null($comp)) return;
        $comp->update($request->all());
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $comp = $this->findByName($name)->first();
        if(is_null($comp)) return;
        $comp->delete();
        return;
    }

    /**
     * Returns a specified resources of a given competition
     * 
     * @param \Illuminate\Http\Request  $request
     * @param string $name
     * @param string $resource (leaderboard, problems, teams, submissions)
     * @return \Illuminate\Http\Response
     */

    public function showResource(Request $request, $name, $resource)
    {
        $comp = $this->findByName($name)->first();
        if(is_null($comp)) return;
        if(!in_array($resource, $comp->resources)) return;
        $query = $comp->$resource();
        $limit = $request->input('limit');
        if(is_null($limit))
            return $query->get();
        else
            return $query->paginate($limit)->setPath($comp[$resource.'Url'].'?limit='.$limit);
    }
}

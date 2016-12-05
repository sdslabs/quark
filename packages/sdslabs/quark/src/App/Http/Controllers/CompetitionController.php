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
    	$this->validate($request, [
    		'name' => 'bail|required|alpha_dash|unique:competitions,name',
    		'title' => 'required',
    		'description' => 'required',
    		'rules' => 'required',
    		'team_limit' => 'bail|required|integer',
    		'start_at' => 'bail|required|date',
    		'end_at' => 'bail|required|date|after:start_at',
    		'utc' => 'bail|required|accepted'
		]);

		$comp = new Competition($request->all());
        $saved = $comp->save();
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show(Competition $competition, Request $request)
    {
        $user = Auth::user();
        if(!is_null($user))
        {
            $competition->team = TeamController::findByCompetition($competition)->with('members')->first();
        }
        return $competition;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function edit(Competition $competition)
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
    public function update(Request $request, Competition $competition)
    {
    	$this->validate($request, [
    		'name' => 'bail|alpha_dash|unique:competitions,name,'.$competition->id.',id',
    		'team_limit' => 'integer',
    		'start_at' => 'bail|date',
    		'end_at' => 'bail|date|after:start_at',
    		'utc' => 'bail|required_with:start_at,end_at|accepted'
		]);

        $competition->update($request->all());
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competition $competition)
    {
        $competition->delete();
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

    public function showResource(Request $request, Competition $competition, $resource)
    {
        if(!in_array($resource, $competition->resources)) return;
        $query = $competition->$resource();
        $limit = $request->input('limit');
        if(is_null($limit))
            return $query->get();
        else
            return $query->paginate($limit)->setPath($competition[$resource.'Url'].'?limit='.$limit);
    }
}

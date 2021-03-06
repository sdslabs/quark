<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class CompetitionController extends Controller
{

	/**
	 * Applied developer Middleware to all routes which require developer access
	 *
	 */

	public function __construct(Competition $comps)
	{
		$this->competitions = $comps;
		$this->middleware('developer')->only(['create', 'store', 'update', 'edit', 'destroy']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$competitions = $this->competitions->all();
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
		]);

		$comp = App::make(Competition::class, [$request->all()]);
		$comp->save();
		return $comp;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function show(Competition $competition, Request $request)
	{
		// If the user is authenticated, return his team for the competition
		if (!is_null(Auth::user()))
			$competition->team = Auth::user()->teams()->where('competition_id', $competition->id)->first();

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
			'start_at' => 'date',
			'end_at' => 'bail|date|after:start_at',
		]);

		$competition->update($request->all());
		return $competition;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Competition $competition)
	{
		if ($competition->status !== 'Future')
			abort(422, "The competition is either running or over.");

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

	public function showLeaderboard(Request $request, Competition $competition)
	{
		$limit = $request->has('limit') ? $request->limit : 50;
		return $competition->leaderboard()->paginate($limit);
	}

	public function showSubmissions(Request $request, Competition $competition)
	{
		$limit = $request->has('limit') ? $request->limit : 50;
		return $competition->submissions()->paginate($limit);
	}

}

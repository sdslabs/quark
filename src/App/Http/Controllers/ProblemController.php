<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Problem;
use SDSLabs\Quark\App\Models\Competition;
use SDSLabs\Quark\App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

/**
 * Controller to manage the competition problems.  
 */

class ProblemController extends Controller
{

	/**
	 * Initialize class member variables.
	 *
	 * @param \SDSLabs\Quark\App\Models\Competition $comps
	 * @param \SDSLabs\Quark\App\Models\User $users
	 */
	public function __construct(Competition $comps, User $users)
	{
		$this->competitions = $comps;
		$this->users = $users;
		$this->middleware('developer');
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
	 * @api
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'bail|required|alpha_dash|unique:problems,name',
			'title' => 'required',
			'description' => 'required',
			'practice' => 'bail|required|boolean',
			'competition' => 'bail|alpha_dash|exists:competitions,name',
			'creator' => 'bail|exists:users,username',
			'score' => 'bail|required|numeric',
			'answer' => 'required',
		]);

		$problem = App::make(Problem::class, [[
			"name" => $request->name,
			"title" => $request->title,
			"description" => $request->description
		]]);

		if($request->has('practice') && $request->practice)
			$problem->practice = 1;

		if($request->has('competition'))
		{
			$competition = $this->competitions->findByName($request->competition)->first();
			$problem->competition()->associate($competition);
		}

		if($request->has('creator'))
		{
			$creator = $this->users->findByUsername($request->creator)->first();
			$problem->creator()->associate($creator);
		}

		$problem->uploader()->associate(Auth::user());
		$problem->save();

		$solution_controller = App::make(SolutionController::class);
		$solution = $solution_controller->store($request, $problem);

		return $problem;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function edit($name)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * @api
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  SDSLabs\Quark\App\Models\Problem $problem
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Problem $problem)
	{
		$this->validate($request, [
			'name' => 'bail|alpha_dash|unique:problems,name,'.$problem->id.',id',
			'practice' => 'boolean',
			'competition' => 'bail|alpha_dash|exists:competitions,name',
			'creator' => 'bail|exists:users,username',
			'score' => 'numeric'
		]);

		if (($request->has('score') || $request->has('answer')) && $problem->hasSubmissions())
			abort(422, "Problem has some submissions, so the score/answer can't be changed.");

		if($request->exists('competition'))
		{
			if($request->competition === "")
				$problem->competition_id = null;
			else
			{
				$competition = $this->competitions->findByName($request->competition)->first();
				$problem->competition()->associate($competition);
			}
		}

		if($request->has('creator'))
		{
			$creator = $this->users->findByUsername($request->creator)->firstOrFail();
			$problem->creator()->associate($creator);
		}

		$problem->uploader()->associate(Auth::user());
		$problem->save();

		$solution_controller = App::make(SolutionController::class);
		$solution = $solution_controller->update($request, $problem);

		return $problem;
	}

	/**
	 * Remove the specified resource from storage.
	 * @api
	 * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  SDSLabs\Quark\App\Models\Problem $problem
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Problem $problem)
	{
		if($problem->hasSubmissions())
			abort(422, "The problem has some submissions, so it can't be deleted.");

		$solution_controller = App::make(SolutionController::class);
		$solution = $solution_controller->delete($request, $problem);

		$problem->delete();

		return;
	}
}

<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Problem;

class ProblemController extends Controller
{

	public function __construct()
	{
		$this->middleware('developer');
	}

	public static function findByName($name)
	{
		return Problem::where("name", $name);
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
			'name' => 'bail|required|alpha_dash|unique:problems,name',
			'title' => 'required',
			'description' => 'required',
			'practice' => 'bail|required|boolean',
			'competition' => 'bail|alpha_dash|exists:competitions,name',
			'creator' => 'bail|exists:users,username',
			'score' => 'bail|required|numeric',
			'answer' => 'required',
		]);

		$problem = new Problem([
			"name" => $request->name,
			"title" => $request->title,
			"description" => $request->description
		]);

		if($request->has('practice') && $request->practice)
			$problem->practice = 1;

		if($request->has('competition'))
		{
			$competition = CompetitionController::findByName($request->competition)->first();
			$problem->competition()->associate($competition);
		}

		if($request->has('creator'))
		{
			$creator = UserController::findByName($request->creator)->first();
			$problem->creator()->associate($creator);
		}

		$problem->uploader()->associate(Auth::user());
		$problem->save();

		$solution_controller = new SolutionController;
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
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  string  $name
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
				$competition = CompetitionController::findByName($request->competition)->first();
				$problem->competition()->associate($competition);
			}
		}

		if($request->has('creator'))
		{
			$creator = UserController::findByName($request->creator)->firstOrFail();
			$problem->creator()->associate($creator);
		}

		$problem->uploader()->associate(Auth::user());
		$problem->save();

		$solution_controller = new SolutionController;
		$solution = $solution_controller->update($request, $problem);

		return $problem;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Problem $problem)
	{
		if($problem->hasSubmissions())
			abort(422, "The problem has some submissions, so it can't be deleted.");

		$solution_controller = new SolutionController;
		$solution = $solution_controller->delete($request, $problem);

		$problem->delete();

		return;
	}
}

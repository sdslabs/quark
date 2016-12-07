<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Problem;
use SDSLabs\Quark\App\Models\Competition;

class CompetitionProblemController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Competition $competition)
	{
		$problems = Problem::where('competition_id', $competition->id)->with('competition_submissions')->get();

		$problems->each(function($item) {
			$item['submissions'] = $item->competition_submissions->where('status', 'correct')->count();
			$item->makeHidden('competition_submissions');
		});
		$problems->sortByDesc('submissions');

		return $problems;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Http\Response
	 */
	public function show(Competition $competition, $problem_name)
	{
		$problem = ProblemController::findByName($problem_name)->where('competition_id', $competition->id)->firstOrFail();

		$problem->load('competition_submissions.user', 'creator');

		return $problem;
	}

}

<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Competition;

use App\Http\Controllers\Controller;


class CompetitionProblemController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Competition $competition)
	{
		$problems = $competition->problems()->with('competition_submissions')->get();

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
		$problem = $competition->problems()->where('name', $problem_name)->firstOrFail();

		$problem->load('competition_submissions.team', 'creator');

		return $problem;
	}

}

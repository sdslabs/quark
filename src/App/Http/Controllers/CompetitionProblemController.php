<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Problem;
use SDSLabs\Quark\App\Models\Competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller to manage the problems of a competition.  
 */

class CompetitionProblemController extends Controller
{
	/**
	 * Display a listing of problems in a competition.
	 * @api
	 * 
	 * @param  \SDSLabs\Quark\App\Models\Competition  $competition
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
	 * Display the specified problem of a competition.
	 * @api
	 * 
	 * @param  \SDSLabs\Quark\App\Models\Competition  $competition
	 * @param  string  $problem_name
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function show(Competition $competition, $problem_name)
	{
		$problem = $competition->problems()->where('name', $problem_name)->firstOrFail();

		$problem->load('competition', 'competition_submissions.team', 'creator');

		return $problem;
	}

}

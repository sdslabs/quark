<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Problem;

class PracticeProblemController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$problems = Problem::where('practice', 1)->with('practice_submissions')->get();

		$problems->each(function($item) {
			$item['submissions'] = $item->practice_submissions->where('status', 'correct')->count();
			$item->makeHidden('practice_submissions');
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
	public function show(Problem $problem)
	{
		if (!$problem->practice)
			abort(404, "Problem not found");

		$problem->load('practice_submissions.user', 'creator');

		return $problem;
	}

}

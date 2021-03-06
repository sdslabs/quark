<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Problem;

use App\Http\Controllers\Controller;

class PracticeProblemController extends Controller
{

	public function __construct(Problem $problems)
	{
		$this->problems = $problems;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$problems = $this->problems->where('practice', 1)->with('practice_submissions')->get();

		$problems->each(function($item) {
			$item['submissions'] = $item->practice_submissions->where('status', 'correct')->count();
			$item->makeVisible('created_at');
			$item->load('creator');
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

		$problem->load('practice_submissions.user', 'creator', 'tags');

		return $problem;
	}

}

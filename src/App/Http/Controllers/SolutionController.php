<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Solution;
use SDSLabs\Quark\App\Models\Problem;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

/**
 * Controller to manage the solutions of problems.  
 */

class SolutionController extends Controller
{

	/**
	 * Store a newly created resource in storage.
	 * @api
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  SDSLabs\Quark\App\Models\Problem $problem
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Problem $problem)
	{
		$solution = App::make(Solution::class, [$request->all()]);

		$solution->problem()->associate($problem);

		$solution->save();

		return $solution;
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
		$solution = $problem->solution;

		$solution->update($request->all());

		return $solution;
	}

	/**
	 * Remove the specified resource from storage.
	 * @api
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  SDSLabs\Quark\App\Models\Problem $problem
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request, Problem $problem)
	{
		$solution = $problem->solution;

		$solution->delete();

		return;
	}

}

<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Solution;
use SDSLabs\Quark\App\Models\Problem;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;


class SolutionController extends Controller
{

	public function store(Request $request, Problem $problem)
	{
		$solution = App::make(Solution::class, [$request->all()]);

		$solution->problem()->associate($problem);

		$solution->save();

		return $solution;
	}

	public function update(Request $request, Problem $problem)
	{
		$solution = $problem->solution;

		$solution->update($request->all());

		return $solution;
	}

	public function delete(Request $request, Problem $problem)
	{
		$solution = $problem->solution;

		$solution->delete();

		return;
	}

}

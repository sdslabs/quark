<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SDSLabs\Quark\App\Models\Solution;
use SDSLabs\Quark\App\Models\Problem;

class SolutionController extends Controller
{

    public function store(Request $request, Problem $problem)
    {
        $solution = new Solution;
        $problem = $problem;

        if($problem->problem_type->name === 'string')
            // TODO: Hash it
            $solution->solution = $request->solution;
        elseif ($problem->problem_type->name === 'file')
            $solution->solution = $request->solution->storeAs('solution', $problem->name);

        if($problem->practice)
        {
            $practice_judge = JudgeController::findByName($request->practice_judge)->first();
            $solution->practice_judge()->associate($practice_judge);
            $solution->practice_score = $request->practice_score;
        }

        if($problem->competition)
        {
            $competition_judge = JudgeController::findByName($request->competition_judge)->first();
            $solution->competition_judge()->associate($competition_judge);
            $solution->competition_score = $request->competition_score;
        }

        $solution->save();
        return $solution;
    }

    public function update(Request $request, Problem $problem)
    {
        $solution = $problem->solution;

        if($request->has('practice_judge'))
        {
            $practice_judge = JudgeController::findByName($request->practice_judge)->first();
            $solution->practice_judge()->associate($practice_judge);
        }

        if($request->has('competition_judge'))
        {
            $competition_judge = JudgeController::findByName($request->competition_judge)->first();
            $solution->competition_judge()->associate($competition_judge);
        }

        if($request->has('practice_score'))
            $solution->practice_score = $request->practice_score;

        if($request->has('competition_score'))
            $solution->competition_score = $request->competition_score;

        if($request->has('solution'))
        {
            if($problem->problem_type->name === 'string')
                // TODO: Hash it
                $solution->solution = $request->solution;
            elseif ($problem->problem_type->name === 'file')
                $solution->solution = $request->solution->storeAs('solution', $problem->name);
        }
        return $solution;
    }
}

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
        {
            // TODO: Hash it
            $solution->solution = $request->solution;
        }
        elseif ($problem->problem_type->name === 'file')
        {
            $solution->solution = $request->solution->storeAs('solution', $problem->name);
        }

        if($problem->practice)
        {
            $practice_judge = JudgeController::findByName($request->practice_judge)->first();
            if(is_null($practice_judge))
                throw new \Excpetion("Invalid Practice Judge");
            $solution->practice_judge()->associate($practice_judge);

            $practice_score = $request->practice_score;
            if(is_null($practice_score))
                throw new \Exception("Practice score is required for practice problems");
            $solution->practice_score = $practice_score;
        }

        if($problem->competition)
        {
            $competition_judge = JudgeController::findByName($request->competition_judge)->first();
            if(is_null($competition_judge))
                throw new \Exception("Invalid Competition Judge");
            $solution->competition_judge()->associate($competition_judge);

            $competition_score = $request->competition_score;
            if(is_null($competition_score))
                throw new \Exception("Competition score is required for competition problems");
            $solution->competition_score = $competition_score;
        }

        $solution->save();
        return $solution;
    }

    public function update(Request $request, Problem $problem)
    {
        $solution = $problem->solution;
        
        if ($problem->hasPracticeLogs() > 0 &&
            ($request->has('practice_judge') || $request->has('practice_score') || $request->has('solution')))
            throw new \Exception("The problem has practice submissions");

        if ($problem->hasCompetitionLogs() > 0 &&
            ($request->has('competition_judge') || $request->has('competition_score') || $request->has('solution')))
            throw new \Exception("The problem has competition submissions");

        if($request->has('practice_judge'))
        {
            $practice_judge = JudgeController::findByName($request->practice_judge)->first();
            if(is_null($practice_judge))
                throw new \Excpetion("Invalid Practice Judge");
            $solution->practice_judge()->associate($practice_judge);
        }

        if($request->has('competition_judge'))
        {
            $competition_judge = JudgeController::findByName($request->competition_judge)->first();
            if(is_null($competition_judge))
                throw new \Exception("Invalid Competition Judge");
            $solution->competition_judge()->associate($competition_judge);
        }

        if($request->has('practice_score'))
        {
            $solution->practice_score = $request->practice_score;
        }
        if($request->has('competition_score'))
        {
            $solution->competition_score = $request->competition_score;
        }

        if($request->has('solution'))
        {
            if($problem->problem_type->name === 'string')
            {
                // TODO: Hash it
                $solution->solution = $request->solution;
            }
            elseif ($problem->problem_type->name === 'file')
            {
                $solution->solution = $request->solution->storeAs('solution', $problem->name);
            }
            $changes['solution']['new'] = $solution->solution;
        }
        return $solution;
        // TODO: Send changes for rejudging.
    }
}

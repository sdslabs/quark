<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SDSLabs\Quark\App\Models\Solution;
use SDSLabs\Quark\App\Models\Problem;

class InvalidInputException extends \Exception{}

class SolutionController extends Controller
{
    protected $solution;
    protected $problem;

    public function store(Request $request, Problem $problem)
    {
        $this->solution = new Solution;
        $this->problem = $problem;
        if($this->problem->problem_type->name === 'string')
        {
            // TODO: Hash it
            $this->solution->solution = $request->solution;
        }
        elseif ($this->problem->problem_type->name === 'file')
        {
            $this->solution->solution = $request->solution->storeAs('solution', $this->problem->name);
        }

        if($this->problem->practice)
        {
            $practice_judge = JudgeController::findByName($request->practice_judge)->first();
            if(is_null($practice_judge))
                throw new InvalidInputExcpetion("Invalid Practice Judge");
            $this->solution->practice_judge()->associate($practice_judge);

            $practice_score = $request->practice_score;
            if(is_null($practice_score))
                throw new InvalidInputException("Practice score is required for practice problems");
            $this->solution->practice_score = $practice_score;
        }

        if($this->problem->competition)
        {
            $competition_judge = JudgeController::findByName($request->competition_judge)->first();
            if(is_null($competition_judge))
                throw new InvalidInputException("Invalid Competition Judge");
            $this->solution->competition_judge()->associate($competition_judge);

            $competition_score = $request->competition_score;
            if(is_null($competition_score))
                throw new InvalidInputException("Competition score is required for competition problems");
            $this->solution->competition_score = $competition_score;
        }

        $this->addPropertiesToSolution($request);

        $this->solution->save();
        return $this->solution;
    }

    public function addPropertiesToSolution(Request $request)
    {
        return $this->solution;
    }
}

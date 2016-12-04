<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\Problem;

class ProblemController extends Controller
{
    public function __construct()
    {
        $this->middleware('developer')->except(['index', 'show']);
    }


    public static function findByName($name)
    {
        return Problem::where("name", $name);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $problems = Problem::where('practice', 1)->get();
        return $problems;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'bail|required|alpha_dash|unique:problems,name',
    		'title' => 'required',
    		'description' => 'required',
    		'problem_type' => 'bail|required|alpha_dash|model_presence:ProblemType,name',
    		'practice' => 'boolean',
    		'competition' => 'bail|alpha_dash|not_finished_competition',
    		'creator' => 'bail|model_presence:User,name',
    		'solution' => 'required',
    		'practice_judge' => 'bail|required_if:practice,1|alpha_dash|model_presence:Judge,name',
    		'practice_score' => 'bail|required_if:practice,1|numeric',
    		'competition_judge' => 'bail|required_with:competition|alpha_dash|model_presence:Judge,name',
    		'competition_score' => 'bail|required_with:competition|numeric'
    	]);

        $prob = new Problem([
            "name" => $request->name,
            "title" => $request->title,
            "description" => $request->description
        ]);

        if($request->has('practice') && $request->practice)
            $prob->practice = 1;

        $prob_type = ProblemTypeController::findByName($request->problem_type)->first();
        $prob->problem_type()->associate($prob_type);

        if($request->has('competition'))
        {
        	$comp = CompetitionController::findByName($request->competition)->first();
            $prob->competition()->associate($comp);
        }

        if($request->has('creator'))
        {
            $creator = UserController::findByName($request->creator)->first();
            $prob->creator()->associate($creator);
        }

        $solution_controller = new SolutionController;
        $solution = $solution_controller->store($request, $prob);
        $prob->solution()->associate($solution);
        $prob->uploader()->associate(Auth::user());
        $prob->save();
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $prob = $this->findByName($name)->where('practice', 1)->with('solved_by')->first();
        if(is_null($prob)) return;
        return $prob;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        $prob = ProblemController::findByName($name)->first();
        if (is_null($prob))
            return;

    	$this->validate($request, [
    		'name' => 'bail|alpha_dash|unique:problems,name,'.$prob->id.',id',
    		'problem_type' => 'bail|alpha_dash|model_presence:ProblemType,name',
    		'practice' => 'boolean',
    		'competition' => 'bail|alpha_dash|not_finished_competition',
    		'creator' => 'bail|model_presence:User,name',
    		'practice_judge' => 'bail|alpha_dash|model_presence:Judge,name',
    		'practice_score' => 'numeric',
    		'competition_judge' => 'bail|alpha_dash|model_presence:Judge,name',
    		'competition_score' => 'numeric'
    	]);

        if ($request->has('practice'))
        {
        	$sol = $prob->solution()->with('practice_judge')->first();

        	if(is_null($sol->practice_judge) || is_null($sol->practice_score))
        		$this->validate($request, [
        			'practice_judge': 'required',
        			'practice_score': 'required'
        		]);

            $prob->practice = $request->practice;
        }

        if($request->has('competition'))
        {
        	$sol = $prob->solution()->with('competition_judge')->first();

        	if(is_null($sol->practice_judge) || is_null($sol->competition_score))
        		$this->validate($request, [
        			'competition_judge': 'required',
        			'competition_score': 'required'
        		]);

        	$comp = CompetitionController::findByName($competition)->first();
            $prob->competition()->associate($comp);
        }

        if ($problem->hasPracticeLogs() > 0)
        	// TODO: Return error message as these parameters can not be updated because of practice logs.
        	$this->validate($request, [
        		'practice_judge' => '',
        		'practice_score' => '',
        		'solution' => ''
        	]);

        if ($problem->hasCompetitionLogs() > 0)
        	// TODO: Return error message as these parameters can not be updated because of competition logs.
        	$this->validate($request, [
        		'competition_judge' => '',
        		'competition_score' => '',
        		'solution' => ''
        	]);

        if($request->has('problem_type'))
        	// TODO: Handle this!!
            return "Problem Type can't be updated";

        if ($request->has('name'))
            $prob->name = $request->name;

        if ($request->has('title'))
            $prob->title = $request->title;

        if ($request->has('description'))
            $prob->description = $request->description;

        if($request->has('creator'))
        {
            $creator = UserController::findByName($request->creator)->first();
            $prob->creator()->associate($creator);
        }


        $solution_controller = new SolutionController;
        $solution = $solution_controller->update($request, $prob);
        $prob->solution()->associate($solution);
        $prob->save();
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $prob = ProblemController::findByName($name)->first();
        if($prob->hasSubmissions())
            return "The problem has some submissions";
        $prob->delete();
        $prob->solution->delete();
        return;
    }
}

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
        $prob = new Problem([
            "name" => $request->name,
            "title" => $request->title,
            "description" => $request->description
        ]);
        if($request->has('practice') && $request->practice === 1)
                $prob->practice = 1;

        if($request->has('problem_type'))
        {
            $prob_type = ProblemTypeController::findByName($request->problem_type)->first();
            if(is_null($prob_type))
                return "Invalid Problem Type Name";
            $prob->problem_type()->associate($prob_type);
        }
        else
        {
            return "Problem type is required!!";
        }

        if($request->has('competition'))
        {
            $comp = CompetitionController::findByName($request->competition)->first();
            if(is_null($comp))
                return "Invalid Competition Name";
            if($comp->status === 'Finished')
                return "The competition has already ended";
            $prob->competition()->associate($comp);
        }

        if($request->has('creator'))
        {
            $creator = UserController::findByName($request->creator)->first();
            if(is_null($creator))
                return "Invalid Creator Name";
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
        if ($request->has('name'))
            $prob->name = $request->name;
        if ($request->has('title'))
            $prob->title = $request->title;
        if ($request->has('description'))
            $prob->description = $request->description;
        if ($request->has('practice') &&
           ($request->practice === 0 || $request->practice === 1))
            $prob->practice = $request->practice;

        if($request->has('competition'))
        {
            $comp = CompetitionController::findByName($request->competition)->first();
            if(is_null($comp))
                return "Invalid competition name!";
            if($comp->status === 'Finished')
                return "The competition has already ended!";
            $prob->competition()->associate($comp);
        }

        if($request->has('problem_type'))
        {
            return "Problem Type can't be updated";
        }

        if($request->has('creator'))
        {
            $creator = UserController::findByName($request->creator)->first();
            if(is_null($creator))
                return "Invalid Creator Name";
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

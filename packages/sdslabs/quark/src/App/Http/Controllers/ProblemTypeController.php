<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SDSLabs\Quark\App\Models\ProblemType;

class ProblemTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('developer');
    }


    public static function findByName($name)
    {
        return ProblemType::where("name", $name);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prob_types = ProblemType::get();
        return $prob_types;
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
    		'name' => 'bail|required|alpha_dash|unique:problem_types,name',
    		'title' => 'required',
    		'description' => 'required'
		]);
    	$prob_type = new ProblemType($request->all());
    	$prob_type->save();
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show(ProblemType $problem_type)
    {
    	return $problem_type;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function edit(ProblemType $problem_type)
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
    public function update(Request $request, ProblemType $problem_type)
    {
    	if($request->has('name'))
    		return "Problem Type name can't be updated";

    	$problem_type->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProblemType $problem_type)
    {
    	if($problem_type->problems()->count() > 0)
    		return "There are problems associated with this type, so it cannot be deleted";

    	$problem_type->delete();
    }
}

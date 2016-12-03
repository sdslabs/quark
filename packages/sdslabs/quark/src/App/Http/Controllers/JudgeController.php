<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SDSLabs\Quark\App\Models\Judge;

class JudgeController extends Controller
{

    /**
     * Applied developer Middleware to all routes which require developer access
     * 
     */
    public function __construct()
    {
        $this->middleware('developer');
    }

    public static function findByName($name)
    {
        return Judge::where('name', $name);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $competitions = Judge::get();
        return $competitions;
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
        $judge = new Judge($request->all());
        $judge->save();
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
        $judge = JudgeController::findByName($name)->first();
        if(is_null($judge))
            return;
        return $judge;
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
        $judge = JudgeController::findByName($name)->first();
        if(is_null($judge))
            return;
        $judge->update($request->all());
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
        $judge = JudgeController::findByName($name)->first();
        if(is_null($judge))
            return;
        $judge->delete();
        return;
    }

    /**
     * Returns a specified resources of a given competition
     * 
     * @param \Illuminate\Http\Request  $request
     * @param string $name
     * @param string $resource (leaderboard, problems, teams, submissions)
     * @return \Illuminate\Http\Response
     */

}

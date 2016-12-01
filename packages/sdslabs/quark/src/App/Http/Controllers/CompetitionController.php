<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use SDSLabs\Quark\App\Models\Competition;

class CompetitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('developer')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = time();
        $future_competitions = array();
        $running_competitions = array();
        $finished_competitions = array();

        $all_competitions = Competition::orderBy('created_at', 'DESC')->get();

        foreach($all_competitions as $competition)
        {
            if(($competition->start_at <= $now) && ($competition->end_at >= $now))
                array_push($running_competitions, $competition);
            else if($competition->end_at < $now)
                array_push($finished_competitions, $competition);
            else
                array_push($future_competitions, $competition);
        }
        return [
            "competitions" => [
                'all' => $all_competitions,
                'future' => $future_competitions,
                'running' => $running_competitions,
                'finished' => $finished_competitions
            ]
        ];
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

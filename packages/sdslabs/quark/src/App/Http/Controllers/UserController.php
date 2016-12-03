<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\User;

class UserController extends Controller
{

	public function __construct()
    {
        $this->middleware('developer')->only(['destroy']);
    }

	public static function findByName($name)
    {
        return User::where("username", $name);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$users = User::paginate(50);
        return $users;
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
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $user = $this->findByName($name)->with('all_teams', 'submissions', 'problems_created', 'owned_teams', 'invites_sent', 'invites_received')->first();
        if(is_null($user)) return;

        if(!is_null(Auth::user()) && Auth::user()->id == $user->id)
            $user->makeVisible('email');
        else
            $user->makeHidden('owned_teams', 'invites_received', 'invites_sent');

        return $user;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $user = $this->findByName($name)->first();
        if(is_null($user)) return;
        $user->delete();
        return;
    }
}

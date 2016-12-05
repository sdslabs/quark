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
        $this->middleware('auth')->except(['index', 'show']);
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
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $user = UserController::findByName($name)->with('all_teams', 'submissions', 'problems_created');
        if(!is_null(Auth::user()))
        {
            if ($name === Auth::user()->name)
                $user->with('owned_teams', 'invites_sent', 'invites_received', 'email');
            if(Auth::user()->isDeveloper())
                $user->with('roles');
        }
        return $user->first();
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
    	$this->validate($request, [
    		'username' => 'bail|alpha_dash|unique:users,username',
    		'fullname' => 'alpha'
    	]);
        if($name === Auth::user()->username)
        {
            Auth::user()->update($request->all());
        }
        elseif(Auth::user()->isDeveloper())
        {
            $user = UserController::findByName($name)->first();
            $user->update($request->all());
        }
        else
        {
            return "You don't have the permission to update this user.";
        }
    }

    public function indexRole($user_name)
    {
    	$user = UserController::findByName($user_name)->first();
    	if(is_null($user))
    		return;

    	return $user->roles;
    }

    public function showRole($user_name, $role_name)
    {
    	$user = UserController::findByName($user_name)->first();
    	if(is_null($user))
    		return;

    	return $user->roles()->where('name', $role_name)->first();
    }

    public function grantRole($user_name, $role_name)
    {
    	$user = UserController::findByName($user_name)->first();
    	$role = RoleController::findByName($role_name)->first();

    	if(is_null($user)) return "Invalid user";
    	if(is_null($role)) return "Invalid role";

    	if($user->roles()->where('name', $role_name)->count() > 0)
    		return "{$user_name} is already a {$role_name}";

    	$user->roles()->attach($role);
		return;
    }

    public function revokeRole($user_name, $role_name)
    {
    	$user = UserController::findByName($user_name)->first();
    	$role = RoleController::findByName($role_name)->first();

    	if(is_null($user)) return "Invalid user";
    	if(is_null($role)) return "Invalid role";

    	$user->roles()->detach($role);
		return;
    }
}

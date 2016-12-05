<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('developer')->only(['indexRole', 'showRole', 'grantRole', 'revokeRole']);
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
    public function show(User $user)
    {
        $user->load('all_teams', 'submissions', 'problems_created');
        if(!is_null(Auth::user()))
        {
        	$attributes = [];
        	$visible = [];
            if ($user->username === Auth::user()->username || Auth::user()->isDeveloper())
            {
                array_push($attributes, 'owned_teams', 'invites_sent', 'invites_received');
                $user->makeVisible('email');
            }
            if (Auth::user()->isDeveloper())
                array_push($attributes, 'roles');
        }
        return $user->load($attributes);
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
    public function update(Request $request, User $user)
    {
    	$this->validate($request, [
    		'username' => 'bail|alpha_dash|unique:users,username,'.$user->id.',id',
    		'fullname' => 'alpha'
    	]);

        if($user->username === Auth::user()->username)
            Auth::user()->update($request->all());
        elseif(Auth::user()->isDeveloper())
            $user->update($request->all());
        else
            return "You don't have the permission to update this user.";
    }

    public function indexRole(User $user)
    {
    	return $user->roles;
    }

    public function showRole(User $user, Role $role)
    {
    	return $user->roles()->where('name', $role->name)->first();
    }

    public function grantRole(User $user, Role $role)
    {
    	if($user->roles()->where('name', $role->name)->count() > 0)
    		return "{$user->username} is already a {$role->name}";

    	$user->roles()->attach($role);
		return;
    }

    public function revokeRole(User $user, Role $role)
    {
    	$user->roles()->detach($role);
		return;
    }
}

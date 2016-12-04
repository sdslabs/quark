<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SDSLabs\Quark\App\Models\Role;

class RoleController extends Controller
{
	public function __construct()
    {
        $this->middleware('developer');
    }

    public static function findByName($name)
    {
        return Role::where("name", $name);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all()->with('id');
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
        $role = new Role($request->all());
    	$role->save();
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
        $user = RoleController::findByName($name)->with('id');
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
        $role = RoleController::findByName($name)->first();
    	if(is_null($role))
    		return;
    	$role->update($request->all());
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
        $role = $this->findByName($name)->first();
        if(is_null($role)) return;
        $role->users()->detach();
        $role->delete();
        return;
    }

    /**
     * Revoke specified row in pivot table.
     *
     * @param  string  $user_name
     * @param  string  $role_name
     * @return \Illuminate\Http\Response
     */
    public function revoke($user_name,$role_name)
    {
    	$user_id = DB::table('users')->where('username', $user_name)->value('id');
    	$role_id = DB::table('roles')->where('name', $role_name)->value('id');

    	if(is_null($user_id)) return "Invalid user";
    	if(is_null($role_id)) return "Invalid role";

    	DB::table('user_role_maps')
    	->where('user_id', $user_id)
    	->where('role_id', $role_id)
    	->update(array('deleted_at' => DB::raw('NOW()')));
		return; 
    }

    /**
     * Restore specified row in pivot table.
     *
     * @param  string  $user_name
     * @param  string  $role_name
     * @return \Illuminate\Http\Response
     */
    public function restore($user_name,$role_name)
    {
    	$user_id = DB::table('users')->where('username', $user_name)->value('id');
    	$role_id = DB::table('roles')->where('name', $role_name)->value('id');

    	if(is_null($user_id)) return "Invalid user";
    	if(is_null($role_id)) return "Invalid role";

    	DB::table('user_role_maps')
    	->where('user_id', $user_id)
    	->where('role_id', $role_id)
    	->update(array('deleted_at' => NULL));
		return; 
    }
}
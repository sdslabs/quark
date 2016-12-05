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
        $roles = Role::with('users')->get();
        return $roles;
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
    		'name' => 'bail|required|alpha_dash|unique:roles,name',
    		'title' => 'required',
    		'description' => 'required'
		]);
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
        $role = RoleController::findByName($name)->with('users')->first();
        if(is_null($role))
        	return;
        return $role;
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

    	$this->validate($request, [
    		'name' => 'bail|alpha_dash|unique:roles,name,'.$role->id.',id'
		]);

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
        if(is_null($role))
        	return;
        $role->users()->detach();
        $role->delete();
        return;
    }

}

<?php

Route::get('home', 'HomeController@index');

Route::resource('competitions.teams', 'TeamController');
Route::get('competitions/{name}/{resource}', 'CompetitionController@showResource');

Route::resource('competitions', 'CompetitionController');

Route::resource('problems', 'ProblemController');

Route::resource('problemTypes', 'ProblemTypeController');

Route::resource('judges', 'JudgeController');

Route::resource('users', 'UserController', ['except' => ['destroy', 'create', 'store']]);

Route::resource('roles', 'RoleController');

Route::post('roles/revoke/{user_name}/{role_name}', 'RoleController@revoke');
Route::post('roles/restore/{user_name}/{role_name}', 'RoleController@restore');


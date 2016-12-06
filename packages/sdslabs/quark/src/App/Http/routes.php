<?php

Route::get('home', 'HomeController@index');

Route::resource('competitions.teams', 'TeamController');
Route::get('competitions/{name}/{resource}', 'CompetitionController@showResource');

Route::resource('competitions', 'CompetitionController');

Route::resource('problems', 'ProblemController');

Route::resource('problem_types', 'ProblemTypeController');

Route::resource('judges', 'JudgeController');

Route::resource('users', 'UserController', ['except' => ['destroy', 'create', 'store']]);

Route::resource('roles', 'RoleController');

Route::get('users/{user}/roles/', 'UserController@indexRole');
Route::get('users/{user}/roles/{role}', 'UserController@showRole');
Route::post('users/{user}/roles/{role}', 'UserController@grantRole');
Route::delete('users/{user}/roles/{role}', 'UserController@revokeRole');

Route::post('competitions/{competition}/teams/{team}/invite/{user}', 'CompetitionInvitesController@inviteUser');
Route::post('competitions/{competition}/teams/{team}/join', 'CompetitionInvitesController@joinTeam');
Route::get('/acceptInvite', 'CompetitionInvitesController@acceptInvite');
Route::get('/cancelInvite', 'CompetitionInvitesController@cancelInvite');

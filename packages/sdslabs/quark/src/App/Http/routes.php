<?php

Route::get('home', 'HomeController@index');

Route::resource('competitions.teams', 'TeamController');
Route::get('competitions/{name}/{resource}', 'CompetitionController@showResource');

Route::resource('competitions', 'CompetitionController');

Route::resource('problems', 'ProblemController');

Route::resource('problemTypes', 'ProblemTypeController');

Route::resource('judges', 'JudgeController');

Route::resource('users', 'UserController', ['except' => ['destroy', 'create', 'store']]);

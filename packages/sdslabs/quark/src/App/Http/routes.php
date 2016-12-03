<?php

Route::get('home', 'HomeController@index');
Route::resource('competitions.teams', 'TeamController');
Route::get('competitions/{name}/{resource}', 'CompetitionController@showResource');
Route::resource('competitions', 'CompetitionController');
Route::resource('problems', 'ProblemController');
Route::resource('user', 'UserController');
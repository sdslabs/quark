<?php

Route::get('home', 'HomeController@index');
Route::resource('competition.team', 'TeamController');
Route::get('competition/{name}/{resource}', 'CompetitionController@showResource');
Route::resource('competition', 'CompetitionController');

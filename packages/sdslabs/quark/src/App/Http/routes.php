<?php

Route::get('home', 'HomeController@index');
Route::get('competition/{name}/leaderboard', 'CompetitionController@showLeaderboard');
Route::resource('competition', 'CompetitionController');
<?php

Route::get('home', 'HomeController@index');
Route::get('competition/{name}/{resource}', 'CompetitionController@showResource');
Route::resource('competition', 'CompetitionController');
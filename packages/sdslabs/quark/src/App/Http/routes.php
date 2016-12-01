<?php

Route::get('home', 'HomeController@index');
Route::resource('competition', 'CompetitionController');
<?php

<<<<<<< HEAD
Route::get('home', 'HomeController@index');
=======

Route::group(['middleware' => ['developer']], function() {

	Route::get('home', ["middleware" => "auth", 'HomeController@index']);

});

>>>>>>> 0bfcc420784f4ac7df0a3dd9a21c765e340c1d17

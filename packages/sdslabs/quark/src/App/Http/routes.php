<?php


Route::group(['middleware' => ['developer']], function() {

	Route::get('home', ["middleware" => "auth", 'HomeController@index']);

});


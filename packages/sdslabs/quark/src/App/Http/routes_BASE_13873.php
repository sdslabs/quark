<?php


Route::get('home', ["middleware" => "auth", 'HomeController@index']);

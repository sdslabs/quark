<?php

Route::resource('problems', 'ProblemController', ['except' => 'index', 'show']);

Route::get('problems', 'PracticeProblemController@index');
Route::get('problems/{problem}', 'PracticeProblemController@show');
Route::post('problems/{problem}/submit', 'SubmissionController@practiceSubmission');

Route::get('competitions/{competition}/problems', 'CompetitionProblemController@index');
Route::get('competitions/{competition}/problems/{problem}', 'CompetitionProblemController@show');
Route::post('competitions/{competition}/problems/{problem}/submit', 'SubmissionController@competitionSubmission');

Route::resource('competitions.teams', 'TeamController');

Route::get('competitions/{competition}/leaderboard', 'CompetitionController@showLeaderboard');
Route::get('competitions/{competition}/submissions', 'CompetitionController@showSubmissions');

Route::resource('competitions', 'CompetitionController');

Route::resource('users', 'UserController', ['except' => ['destroy', 'create', 'store']]);

Route::post('competitions/{competition}/teams/{team}/invite/{user}', 'CompetitionInvitesController@inviteUser');
Route::post('competitions/{competition}/teams/{team}/join', 'CompetitionInvitesController@joinTeam');
Route::get('/acceptInvite', 'CompetitionInvitesController@acceptInvite');
Route::get('/cancelInvite', 'CompetitionInvitesController@cancelInvite');

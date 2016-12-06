<?php

Route::resource('problems', 'PracticeProblemController');

Route::resource('competitions.problems', 'CompetitionProblemController');
Route::resource('competitions.teams', 'TeamController');

Route::get('competitions/{competition}/leaderboard', 'CompetitionController@showLeaderboard');
Route::get('competitions/{competition}/submissions', 'CompetitionController@showSubmissions');

Route::resource('competitions', 'CompetitionController');

Route::resource('users', 'UserController', ['except' => ['destroy', 'create', 'store']]);

Route::post('competitions/{competition}/teams/{team}/invite/{user}', 'CompetitionInvitesController@inviteUser');
Route::post('competitions/{competition}/teams/{team}/join', 'CompetitionInvitesController@joinTeam');
Route::get('/acceptInvite', 'CompetitionInvitesController@acceptInvite');
Route::get('/cancelInvite', 'CompetitionInvitesController@cancelInvite');

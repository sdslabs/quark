<?php

// Problem Management Routes
Route::resource('problems', 'ProblemController', ['except' => 'index', 'show']);

// Practice Problem Routes
Route::get('problems', 'PracticeProblemController@index')->name('practice.problems.index');
Route::get('problems/{problem}', 'PracticeProblemController@show')->name('practice.problems.show');
Route::post('problems/{problem}/submit', 'SubmissionController@practiceSubmission')->name('practice.problems.submit');
Route::get('tags/{tagname}/problems/{problem_id}', 'TagController@store')->name('problem.tag.store');

// Competition Problem Routes
Route::get('competitions/{competition}/problems', 'CompetitionProblemController@index')->name('competitions.problems.index');
Route::get('competitions/{competition}/problems/{problem}', 'CompetitionProblemController@show')->name('competitions.problems.show');
Route::post('competitions/{competition}/problems/{problem}/submit', 'SubmissionController@competitionSubmission')->name('competitions.problems.submit');

// Competition Team Routes
Route::resource('competitions.teams', 'TeamController');

// Competition Leaderboard and Submission routes
Route::get('competitions/{competition}/leaderboard', 'CompetitionController@showLeaderboard')->name('competitions.leaderboard.show');
Route::get('competitions/{competition}/submissions', 'CompetitionController@showSubmissions')->name('competitions.submissions.show');

// Competition Routes
Route::resource('competitions', 'CompetitionController');

// Use this route to check login status.
Route::get('users/self', 'UserController@showSelf')->name('users.self.show');

// User routes
Route::resource('users', 'UserController', ['except' => ['destroy', 'create', 'store']]);

// Invite routes
Route::post('competitions/{competition}/teams/{team}/invite/{user}', 'CompetitionInvitesController@inviteUser')->name('invites.send');
Route::post('competitions/{competition}/teams/{team}/join', 'CompetitionInvitesController@joinTeam')->name('invites.join');
Route::get('/acceptInvite', 'CompetitionInvitesController@acceptInvite')->name('invites.accept');
Route::get('/cancelInvite', 'CompetitionInvitesController@cancelInvite')->name('invites.cancel');

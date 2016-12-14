<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Problem;
use SDSLabs\Quark\App\Models\Competition;
use SDSLabs\Quark\App\Models\PracticeSubmission;
use SDSLabs\Quark\App\Models\CompetitionSubmission;
use SDSLabs\Quark\App\Judge\StringComparisonJudge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SubmissionController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function practiceSubmission(Request $request, Problem $problem)
	{
		if (!$problem->practice)
			abort(404, "Problem not found.");

		$this->validate($request, [
			'answer' => 'required'
		]);

		$user = Auth::user();

		$submissions = $problem->practice_submissions()->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

		if ($submissions->where('status', 'correct')->count() > 0)
			abort(422, "You've already solved this problem.");

		if ($submissions->count() > 0 &&
			time() - $submissions[0]->created_at->timestamp < 30)
			abort(422, "Please do not bruteforce!");

		$submission = app()->make(PracticeSubmission::class, [[
			'submission' => $request->answer,
			'status' => 'pending'
		]]);

		$submission->user()->associate($user);
		$submission->problem()->associate($problem);
		$submission->save();

		$judge = new StringComparisonJudge($problem->solution, $submission);
		return $judge->judge();
	}

	public function competitionSubmission(Request $request, Competition $competition, $problem_name)
	{
		if ($competition->status !== 'Running')
			abort(404, "Competition is not running!");

		$problem = $competition->problems()->where('name', $problem_name)->firstOrFail();

		$this->validate($request, [
			'answer' => 'required'
		]);

		$user = Auth::user();

		$team = $user->teams()->where('competition_id', $competition->id)->first();

		if (is_null($team))
			abort(422, "You are not a member of any team!");

		$submissions = $problem->competition_submissions()->where('team_id', $team->id)->orderBy('created_at')->get();

		if ($submissions->where('status', 'correct')->count() > 0)
			abort(422, "Your team already solved this problem");

		if ($submissions->count() > 0 &&
			time() - $submissions[0]->created_at->timestamp < 30)
			abort(422, "Please do not bruteforce!");

		$submission = app()->make(CompetitionSubmission::class, [[
			'submission' => $request->answer,
			'status' => 'pending'
		]]);

		$submission->team()->associate($team);
		$submission->problem()->associate($problem);
		$submission->save();

		$judge = new StringComparisonJudge($problem->solution, $submission);
		return $judge->judge();
	}

}

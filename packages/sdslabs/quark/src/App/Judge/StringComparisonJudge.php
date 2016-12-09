<?php

namespace SDSLabs\Quark\App\Judge;

use SDSLabs\Quark\App\Models\PracticeSubmission;
use SDSLabs\Quark\App\Models\CompetitionSubmission;
use SDSLabs\Quark\App\Models\Solution;
use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Team;

use Illuminate\Support\Facades\Hash;


class StringComparisonJudge extends Judge
{
	protected $solution;

	protected $submission;

	public function __construct(Solution $solution, $submission)
	{
		$this->solution = $solution;
		$this->submission = $submission;

		parent::__construct($solution->answer, $submission->submission);
	}

	public function judge()
	{
		$match = Hash::check($this->submitted_answer, $this->answer);

		if ($match)
			return $this->handleCorrectSubmission();

		else
			return $this->handleWrongSubmission();
	}

	protected function handleCorrectSubmission()
	{
		$this->submission->update(['status' => 'correct']);

		if ($this->submission instanceof PracticeSubmission)
			$this->updateUserScore();

		elseif ($this->submission instanceof CompetitionSubmission)
			$this->updateTeamScore();

		return "Correct";
	}

	protected function handleWrongSubmission()
	{
		$this->submission->update(['status' => 'wrong']);

		return "Wrong";
	}

	protected function updateUserScore()
	{
		$score = $this->solution->score;

		$user = User::find($this->submission->user_id);
		$user->score += $score;
		$user->save();
	}

	protected function updateTeamScore()
	{
		$score = $this->solution->score;

		$team = Team::find($this->submission->team_id);
		$team->score += $score;
		$team->save();
	}

}

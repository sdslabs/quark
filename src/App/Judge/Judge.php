<?php

namespace SDSLabs\Quark\App\Judge;


abstract class Judge
{
	protected $submission;
	protected $answer;

	public function __construct($answer, $submitted_answer)
	{
		$this->answer = $answer;
		$this->submitted_answer = $submitted_answer;
	}

	abstract public function judge();

	abstract protected function handleResult($result);
}

<?php

namespace SDSLabs\Quark\App\Validators;

use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
	private $_custom_messages = [
		"not_finished_competition" => "The competition has already ended"
	];

	public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array())
	{
		parent::__construct($translator, $data, $rules, $messages, $customAttributes);
		$this->setCustomMessages($this->_custom_messages);
	}

	public function validateNotFinishedCompetition($attribute, $value, $parameters)
	{
		$comp = \SDSLabs\Quark\App\Models\Competition::where('name', $value)->first();
		return (!is_null($comp) && $comp->status !== "Finished");
	}
}

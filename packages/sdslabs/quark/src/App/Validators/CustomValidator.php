<?php

namespace SDSLabs\Quark\App\Validators;

use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
	private $_custom_messages = [
		"model_presence" => "There is no such :attribute",
		"not_finished_competition" => "The competition has already ended"
	];

	public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array())
	{
		parent::__construct($translator, $data, $rules, $messages, $customAttributes);
		$this->setCustomMessages($this->_custom_messages);
	}

	public function validateModelPresence($attribute, $value, $parameters)
	{
		$modelName = $parameters[0];
		$column = $parameters[1];
		$model = 'SDSLabs\\Quark\\App\\Models\\' . $modelName;
		return ($model::where($column, $value)->count() > 0);
	}

	public function validateNotFinishedCompetition($attribute, $value, $parameters)
	{
		$comp = \SDSLabs\Quark\App\Models\Competition::where('name', $value)->first();
		return (!is_null($comp) && $comp->status !== "Finished");
	}
}

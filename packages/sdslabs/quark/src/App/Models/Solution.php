<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class Solution extends Model
{
	protected $table = 'solutions';
	protected $fillable = ['score', 'answer'];
	protected $hidden = ['id', 'problem_id', 'answer'];
	public $timestamps = false;

	public function problem()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Problem');
	}

	public function setAnswerAttribute($answer)
	{
		$this->attributes['answer'] = Hash::make($answer);
	}
}

<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class PracticeSubmission extends Model
{

	protected $table = 'practice_submissions';
	protected $fillable = ['score', 'submission', 'status'];
	protected $hidden = ['id', 'score', 'submission', 'created_at', 'updated_at', 'user_id', 'problem_id'];

	public function problem()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Problem', 'problem_id');
	}

	public function user()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\User', 'user_id');
	}

}

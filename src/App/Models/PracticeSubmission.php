<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;


class PracticeSubmission extends Model
{
	protected $table = 'practice_submissions';
	protected $fillable = ['submission', 'status'];
	protected $hidden = ['id', 'submission', 'created_at', 'updated_at', 'user_id', 'problem_id'];

	public function problem()
	{
		return $this->belongsTo(App::make(Problem::class), 'problem_id');
	}

	public function user()
	{
		return $this->belongsTo(App::make(User::class), 'user_id');
	}

}

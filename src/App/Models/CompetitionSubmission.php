<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class CompetitionSubmission extends Model
{
	protected $table = 'competition_submissions';
	protected $fillable = ['submission', 'status'];
	protected $hidden = ['id', 'submission', 'created_at', 'updated_at', 'team_id', 'problem_id', 'competition_id'];

	public function problem()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Problem', 'problem_id');
	}

	public function team()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Team', 'team_id');
	}

}
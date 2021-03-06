<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;


class CompetitionSubmission extends Model
{
	protected $table = 'competition_submissions';
	protected $fillable = ['submission', 'status'];
	protected $hidden = ['id', 'submission', 'created_at', 'updated_at', 'team_id', 'problem_id', 'competition_id'];

	public function problem()
	{
		return $this->belongsTo(App::make(Problem::class), 'problem_id');
	}

	public function team()
	{
		return $this->belongsTo(App::make(Team::class), 'team_id');
	}

}

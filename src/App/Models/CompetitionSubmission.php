<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

/**
 * Model class used to interact with the table competition_submissions.  
 */
class CompetitionSubmission extends Model
{
	/** @var string $table The table associated with the model. */
	protected $table = 'competition_submissions';
	/** @var array $fillable The attributes that are mass assignable. */
	protected $fillable = ['submission', 'status'];
	/** @var array $hidden The attributes that should be hidden for arrays. */
	protected $hidden = ['id', 'submission', 'created_at', 'updated_at', 'team_id', 'problem_id', 'competition_id'];

	/**
     * Get the problem associated with the competition submission.
     */
	public function problem()
	{
		return $this->belongsTo(App::make(Problem::class), 'problem_id');
	}

	/**
     * Get the team associated with the competition submission.
     */
	public function team()
	{
		return $this->belongsTo(App::make(Team::class), 'team_id');
	}

}

<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

/**
 * Model class used to interact with the table practice_submissions.  
 */
class PracticeSubmission extends Model
{
	/** @var string $table The table associated with the model. */
	protected $table = 'practice_submissions';
	/** @var array $fillable The attributes that are mass assignable. */
	protected $fillable = ['submission', 'status'];
	/** @var array $hidden The attributes that should be hidden for arrays. */
	protected $hidden = ['id', 'submission', 'created_at', 'updated_at', 'user_id', 'problem_id'];

	/**
     * Get the problem associated with the practice submission.
     */
	public function problem()
	{
		return $this->belongsTo(App::make(Problem::class), 'problem_id');
	}

	/**
     * Get the user associated with the practice submission.
     */
	public function user()
	{
		return $this->belongsTo(App::make(User::class), 'user_id');
	}

}

<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * Model class used to interact with the table solutions.  
 */

class Solution extends Model
{
	/** @var string $table The table associated with the model. */
	protected $table = 'solutions';
	/** @var array $fillable The attributes that are mass assignable. */
	protected $fillable = ['score', 'answer'];
	/** @var array $hidden The attributes that should be hidden for arrays. */
	protected $hidden = ['id', 'problem_id', 'answer'];
	/** @var bool $timestamps Indicates if the model should be timestamped. */
	public $timestamps = false;

	/**
     * Get the problem of the solution.
     */
	public function problem()
	{
		return $this->belongsTo(App::make(Problem::class));
	}

	/**
     * Set the answer attribute of the solution.
	 * 
	 * @param string $answer
     */
	public function setAnswerAttribute($answer)
	{
		$this->attributes['answer'] = Hash::make($answer);
	}
}

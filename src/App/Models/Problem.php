<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

/**
 * Model class used to interact with the table problems.  
 */
class Problem extends Model
{
	/** @var string $table The table associated with the model. */
	protected $table = 'problems';
	/** @var array $fillable The attributes that are mass assignable. */
	protected $fillable = ['name', 'title', 'description', 'practice'];
	/** @var array $hidden The attributes that should be hidden for arrays. */
	protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at', 'creator_id', 'uploader_id', 'competition_id'];
	/** @var array $appends The accessors to append to the model's array form. */
	protected $appends = ['solution'];

	/**
     * Get the problem by its name.
	 * 
	 * @param string $name
     */
	public static function findByName($name)
	{
		return Problem::where('name', $name);
	}

	/**
     * Get the route key for the model.
     */
	public function getRouteKeyName()
	{
		return 'name';
	}

	/**
     * Get the competition associated with a problem.
     */
	public function competition()
	{
		return $this->belongsTo(App::make(Competition::class), 'competition_id');
	}

	/**
     * Get the solution associated with a problem.
     */
	public function solution()
	{
		return $this->hasOne(App::make(Solution::class));
	}

	/**
     * Get the creator associated with a problem.
     */
	public function creator()
	{
		return $this->belongsTo(App::make(User::class), 'creator_id');
	}

	/**
     * Get the uploader associated with a problem.
     */
	public function uploader()
	{
		return $this->belongsTo(App::make(User::class), 'uploader_id');
	}

	/**
     * Get all the practice submissions associated with a problem.
     */
	public function practice_submissions()
	{
    	return $this->hasMany(App::make(PracticeSubmission::class));
	}

	/**
     * Get all the competition submissions associated with a problem.
     */
	public function competition_submissions()
	{
		return $this->hasMany(App::make(CompetitionSubmission::class));
	}

	/**
     * Check if a problem has practice submissions.
     */
	public function hasPracticeSubmissions()
	{
		return $this->practice_submissions()->count() > 0 ;
	}

	/**
     * Check if a problem has competition submissions.
     */
	public function hasCompetitionSubmissions()
	{
		return $this->competition_submissions()->count() > 0 ;
	}

	/**
     * Check if a problem has submissions.
     */
	public function hasSubmissions()
	{
		return $this->hasPracticeSubmissions() || $this->hasCompetitionSubmissions();
	}

	/**
     * Get solution attribute of a problem.
     */
	public function getSolutionAttribute()
	{
		return $this->solution()->first();
	}

}

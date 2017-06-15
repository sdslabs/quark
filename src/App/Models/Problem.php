<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;


class Problem extends Model
{
	protected $table = 'problems';
	protected $fillable = ['name', 'title', 'description', 'practice'];
	protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at', 'creator_id', 'uploader_id', 'competition_id'];
	protected $appends = ['solution'];

	public static function findByName($name)
	{
		return Problem::where('name', $name);
	}

	public function getRouteKeyName()
	{
		return 'name';
	}

	public function competition()
	{
		return $this->belongsTo(App::make(Competition::class), 'competition_id');
	}

	public function solution()
	{
		return $this->hasOne(App::make(Solution::class));
	}

	public function creator()
	{
		return $this->belongsTo(App::make(User::class), 'creator_id');
	}

	public function uploader()
	{
		return $this->belongsTo(App::make(User::class), 'uploader_id');
	}

	public function practice_submissions()
	{
    	return $this->hasMany(App::make(PracticeSubmission::class));
	}

	public function competition_submissions()
	{
		return $this->hasMany(App::make(CompetitionSubmission::class));
	}

	public function hasPracticeSubmissions()
	{
		return $this->practice_submissions()->count() > 0 ;
	}

	public function hasCompetitionSubmissions()
	{
		return $this->competition_submissions()->count() > 0 ;
	}

	public function hasSubmissions()
	{
		return $this->hasPracticeSubmissions() || $this->hasCompetitionSubmissions();
	}

	public function getSolutionAttribute()
	{
		return $this->solution()->first();
	}

	public function tags()
	{
		return $this->belongsToMany(App::make(Tags::class),'problem_tags','problem_id','tag_id');
	}

}

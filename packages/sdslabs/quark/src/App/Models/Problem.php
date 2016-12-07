<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Problem extends Model
{

	protected $table = 'problems';
	protected $fillable = ['name', 'title', 'description', 'practice'];
	protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at', 'creator_id', 'uploader_id', 'competition_id'];
	protected $appends = ['solution'];

	public function getRouteKeyName()
	{
		return 'name';
	}

	public function competition()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Competition', 'competition_id');
	}

	public function solution()
	{
		return $this->hasOne('SDSLabs\Quark\App\Models\Solution');
	}

	public function creator()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\User', 'creator_id');
	}

	public function uploader()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\User', 'uploader_id');
	}

	public function practice_submissions()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\PracticeSubmission');
	}

	public function competition_submissions()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\CompetitionSubmission');
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

}

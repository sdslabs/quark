<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Problem extends Model
{

	protected $table = 'problems';
	protected $fillable = ['name', 'title', 'description', 'practice'];
	protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at', 'solution_id', 'creator_id', 'uploader_id', 'competition_id', 'problem_type_id'];
	protected $appends = ['solution', 'creator', 'problem_type'];

	public function competition()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Competitions', 'competition_id');
	}

	public function solution()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Solution', 'solution_id');
	}

	public function creator()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\User', 'creator_id');
	}

	public function uploader()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\User', 'uploader_id');
	}

	public function problem_type()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\ProblemType', 'problem_type_id');
	}

	public function solved_by()
	{
		return [
			"users" => $this->belongsToMany('SDSLabs\Quark\App\Models\User', 'practice_logs', 'problem_id', 'user_id'),
			"teams" => $this->belongsToMany('SDSLabs\Quark\App\Models\Team', 'competition_logs', 'problem_id', 'team_id'),
		];
	}

	public function getSolutionAttribute()
	{
		return $this->solution()->first();
	}

	public function getCreatorAttribute()
	{
		return $this->creator()->first();
	}

	public function getProblemTypeAttribute()
	{
		return $this->problem_type()->first();
	}
}
<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Competition extends Model
{
    
    protected $table = 'competitions';
	protected $fillable = ['name', 'title', 'description', 'rules', 'team_limit', 'start_at', 'end_at'];

	public function problems()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Problem');
	}

	public function teams()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Team');
	}

	public function addProblem(Problem $problem) {
		$this->problems()->save($problem);
	}

	public function getStartTimeAttribute()
	{
		$dateTime = Carbon::createFromTimestamp($this->start_at,'Asia/Kolkata');
		return [$dateTime->format('h:i a'), $dateTime->toFormattedDateString()];
	}

	public function getEndTimeAttribute()
	{
		$dateTime = Carbon::createFromTimestamp($this->end_at,'Asia/Kolkata');
		return [$dateTime->format('h:i a'), $dateTime->toFormattedDateString()];
	}

}

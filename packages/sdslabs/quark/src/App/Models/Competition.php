<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Competition extends Model
{
    
    protected $table = 'competitions';
	protected $fillable = ['name', 'title', 'description', 'rules', 'team_limit', 'start_at', 'end_at'];
	protected $appends = ['status', 'leaderboard'];
	protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

	public function problems()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Problem');
	}

	public function teams()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Team');
	}
	
	public function submissions()
	{
		return $this->hasManyThrough('SDSLabs\Quark\App\Models\CompetitionLog', 'SDSLabs\Quark\App\Models\Team');
	}

	public function getLeaderboardAttribute()
	{
		return route('competition.show', $this->name).'/leaderboard';
	}

	public function getStatusAttribute()
	{
		$now = time();
		$status = "";
		if($this->end_at['timestamp'] < $now)
			$status = "Finished";
		else if($this->start_at['timestamp'] > $now)
			$status = "Future";
		else
			$status = "Running";

		return $status;
	}

	public function getStartAtAttribute($start_at)
	{
		$start_at = strtotime($start_at);
		$dateTime = Carbon::createFromTimestamp($start_at,'Asia/Kolkata');
		return [
			"timestamp" => $start_at,
			"time" => $dateTime->format('h:i a'),
			"date" => $dateTime->toFormattedDateString()
		];
	}

	public function getEndAtAttribute($end_at)
	{
		$end_at = strtotime($end_at);
		$dateTime = Carbon::createFromTimestamp($end_at,'Asia/Kolkata');
		return [
			"timestamp" => $end_at,
			"time" => $dateTime->format('h:i a'),
			"date" => $dateTime->toFormattedDateString()
		];
	}


}

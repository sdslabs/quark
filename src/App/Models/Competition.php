<?php

namespace SDSLabs\Quark\App\Models;

use SDSLabs\Quark\App\Helpers\Leaderboard;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Competition extends Model
{
	protected $table = 'competitions';
	protected $fillable = ['name', 'title', 'description', 'rules', 'team_limit', 'start_at', 'end_at'];
	protected $appends = ['status'];
	protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

	public static function findByName($name)
	{
		return Competition::where('name', $name);
	}

	public function getRouteKeyName()
	{
		return 'name';
	}

	public function problems()
	{
		return $this->hasMany(App::make(Problem::class));
	}

	public function teams()
	{
		return $this->hasMany(App::make(Team::class));
	}

	public function submissions()
	{
		return $this->hasManyThrough(App::make(CompetitionSubmission::class), App::make(Team::class));
	}

	public function leaderboard()
	{
		$leaderboard = Leaderboard::competitionLeaderboard($this);
		return $leaderboard;
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

	public function addTeam(Team $team)
	{
		return $this->teams()->save($team);
	}


}

<?php

namespace SDSLabs\Quark\App\Models;

use SDSLabs\Quark\App\Helpers\Leaderboard;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Model class used to interact with the table Competition.  
 */
class Competition extends Model
{
	/** @var string $table The table associated with the model. */
	protected $table = 'competitions';
	/** @var array $fillable The attributes that are mass assignable. */
	protected $fillable = ['name', 'title', 'description', 'rules', 'team_limit', 'start_at', 'end_at'];
	/** @var array $appends The accessors to append to the model's array form. */
	protected $appends = ['status'];
	/** @var array $hidden The attributes that should be hidden for arrays. */
	protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

	/**
     * Get the competition by its name.
	 * 
	 * @param string $name
     */
	public static function findByName($name)
	{
		return Competition::where('name', $name);
	}

	/**
     * Get the route key for the model.
     */
	public function getRouteKeyName()
	{
		return 'name';
	}

	/**
     * Get all the problems of the competition.
     */
	public function problems()
	{
		return $this->hasMany(App::make(Problem::class));
	}

	/**
     * Get all the teams the competition.
     */
	public function teams()
	{
		return $this->hasMany(App::make(Team::class));
	}
	/**
     * Get all the submission in the competition.
     */
	public function submissions()
	{
		return $this->hasManyThrough(App::make(CompetitionSubmission::class), App::make(Team::class));
	}

	/**
     * Get the leaderboard of the competition.
     */
	public function leaderboard()
	{
		$leaderboard = Leaderboard::competitionLeaderboard($this);
		return $leaderboard;
	}

	/**
     * Get status attribute of the competition.
     */
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

	/**
     * Get start_at attribute of the competition.
	 * 
	 * @param timestamp $start_at
     */
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


	/**
     * Get end_at attribute of the competition.
	 * 
	 * @param timestamp $end_at
     */
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

	/**
     * Add team to a competition
	 * 
	 * @param \SDSLabs\Quark\App\Models\Team $team
     */
	public function addTeam(Team $team)
	{
		return $this->teams()->save($team);
	}


}

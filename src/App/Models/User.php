<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

/**
 * Model class used to interact with the table users.  
 */
class User extends Authenticatable
{
	/** @var string $table The table associated with the model. */	
	protected $table = 'users';
	/** @var array $fillable The attributes that are mass assignable. */
	protected $fillable = ['username', 'fullname'];
	/** @var array $hidden The attributes that should be hidden for arrays. */
	protected $hidden = ['id', 'user_id', 'provider', 'email', 'role', 'created_at', 'updated_at', 'score_updated_at', 'pivot'];
	/** @var array $appends The accessors to append to the model's array form. */
	protected $appends = ['rank'];

	/**
     * Get the user by its username.
	 * 
	 * @param string $name
     */
	public static function findByUsername($name)
	{
		return User::where('username', $name);
	}

	/**
     * Get the route key for the model.
     */
	public function getRouteKeyName()
	{
		return 'username';
	}

	/**
     * Get the submission in the practice section by the user.
     */
	public function submissions()
	{
		return $this->hasMany(App::make(PracticeSubmission::class));
	}

	/**
     * Get the problems created by the user.
     */
	public function problems_created()
	{
		return $this->hasMany(App::make(Problem::class), 'creator_id');
	}

	/**
     * Get the problems uploaded by the user.
     */
	public function problems_uploaded()
	{
		return $this->hasMany(App::make(Problem::class), 'uploader_id');
	}

	/**
     * Get the teams whose user is a member.
     */
	public function teams()
	{
		return $this->belongsToMany(App::make(Team::class), 'user_team_maps', 'user_id', 'team_id');
	}

	/**
     * Get the team whose user is the owner.
     */
	public function owned_teams()
	{
		return $this->hasMany(App::make(Team::class), 'owner_id');
	}

	/**
     * Get the competition in which user participated.
     */
	public function competitions()
	{
		return $this->teams()->with('competition')->get()->pluck('competition.name');
	}

	/**
     * Check whether user is in a competition.
	 * 
	 * @param int $competition_id
     */
	public function isInCompetition($competition_id)
	{
		return $this->teams()->where('competition_id', $competition_id)->count() > 0;
	}

	/**
     * Get the invites to teams recieved by the user.
     */
	public function invites()
	{
		return $this->hasMany(App::make(Invite::class));
	}


	/**
     * Check if the user is a problem developer or not.
     */
	public function isDeveloper()
	{
		return ($this->role === "developer");
	}

	/**
     * Get the rank attribute of the user
     */
	public function getRankAttribute()
	{
		$rank = DB::select('select count(*) + 1 as rank from users where score > :score1 or (score = :score2 and score_updated_at < :score_updated_at)', ['score1' => $this->score, 'score2' => $this->score, 'score_updated_at' => $this->score_updated_at])[0]->rank;
		return $rank;
	}

	/**
     * Create a invite for a team from the user
	 * 
	 * @param \SDSLabs\Quark\App\Models\Team $team
	 * @param string $token
     */
	public function invite(Team $team, $token)
	{
		$invite = App::make(Invite::class);
		$invite->status = 2;
		$invite->token = $token;
		$invite->team()->associate($team);
		return $this->invites()->save($invite);
	}

}

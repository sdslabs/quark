<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Model class used to interact with the table teams.  
 */
class Team extends Model
{
	/** @var string $table The table associated with the model. */
	protected $table = 'teams';
	/** @var array $fillable The attributes that are mass assignable. */
	protected $fillable = ['name', 'score'];
	/** @var array $hidden The attributes that should be hidden for arrays. */
	protected $hidden = ['id', 'competition_id', 'owner_id', 'created_at', 'updated_at', 'pivot', 'score_updated_at'];
	/** @var array $appends The accessors to append to the model's array form. */
	protected $appends = ['rank'];

	/**
     * Get the team by its name.
	 * 
	 * @param string $name
     */
	public static function findByName($name)
	{
		return Team::where('name', $name);
	}

	/**
     * Get the route key for the model.
     */
	public function getRouteKeyName()
	{
		return 'name';
	}

	/**
     * Get the competition in which the team is participating.
     */
	public function competition()
	{
		return $this->belongsTo(App::make(Competition::class), 'competition_id');
	}

	/**
     * Get the owner/leader of the team.
     */
	public function owner()
	{
		return $this->belongsTo(App::make(User::class), 'owner_id');
	}

	/**
     * Get the members of the team.
     */
	public function members()
	{
		return $this->belongsToMany(App::make(User::class), 'user_team_maps', 'team_id', 'user_id');
	}

	/**
     * Get the submissions by the team.
     */
	public function submissions()
	{
		return $this->hasMany(App::make(CompetitionSubmission::class));
	}

	/**
     * Get the invites sent/received by the team.
     */
	public function invites()
	{
		return $this->hasMany(App::make(Invite::class));
	}

	/**
     * Check if the team has a certain member
	 * 
	 * @param \SDSLabs\Quark\App\Models\User $user
     */
	public function hasMember(User $user)
	{
		return $this->members()->where('users.id', $user->id)->count() > 0 ;
	}

	/**
     * Add a user to a team
	 * 
	 * @param \SDSLabs\Quark\App\Models\User $user
     */
	public function addMember(User $user)
	{
		return $this->members()->attach($user);
	}

	/**
     * Get the rank attribute of the team
     */
	public function getRankAttribute()
	{
		$rank = DB::select('select count(*) + 1 as rank from teams where ((score > :score1 or (score = :score2 and score_updated_at < :score_updated_at)) and competition_id = :competition_id)', ['score1' => $this->score, 'score2' => $this->score, 'score_updated_at' => $this->score_updated_at, 'competition_id' => $this->competition_id])[0]->rank;
		return $rank;
	}

	/**
     * Create a invite for a user from the team
	 * 
	 * @param \SDSLabs\Quark\App\Models\User $user
	 * @param string $token
     */
	public function invite(User $user, $token)
	{
		$invite = App::make(Invite::class);
		$invite->status = 1;
		$invite->token = $token;
		$invite->user()->associate($user);
		return $this->invites()->save($invite);
	}

}

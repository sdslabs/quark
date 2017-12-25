<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Team extends Model
{
	protected $table = 'teams';
	protected $fillable = ['name', 'score'];
	protected $hidden = ['id', 'competition_id', 'owner_id', 'created_at', 'updated_at', 'pivot', 'score_updated_at'];
	protected $appends = ['rank'];

	public static function findByName($name)
	{
		return Team::where('name', $name);
	}

	public function getRouteKeyName()
	{
		return 'name';
	}

	public function competition()
	{
		return $this->belongsTo(App::make(Competition::class), 'competition_id');
	}

	public function owner()
	{
		return $this->belongsTo(App::make(User::class), 'owner_id');
	}

	public function members()
	{
		return $this->belongsToMany(App::make(User::class), 'user_team_maps', 'team_id', 'user_id');
	}

	public function submissions()
	{
		return $this->hasMany(App::make(CompetitionSubmission::class));
	}

	public function invites()
	{
		return $this->hasMany(App::make(Invite::class));
	}

	public function hasMember(User $user)
	{
		return $this->members()->where('users.id', $user->id)->count() > 0 ;
	}

	public function addMember(User $user)
	{
		return $this->members()->attach($user);
	}

	public function getRankAttribute()
	{
		$rank = DB::select('select count(*) + 1 as rank from teams where ((score > :score1 or (score = :score2 and score_updated_at < :score_updated_at)) and competition_id = :competition_id)', ['score1' => $this->score, 'score2' => $this->score, 'score_updated_at' => $this->score_updated_at, 'competition_id' => $this->competition_id])[0]->rank;
		return $rank;
	}

	public function invite(User $user, $token)
	{
		$invite = App::make(Invite::class);
		$invite->status = 1;
		$invite->token = $token;
		$invite->user()->associate($user);
		return $this->invites()->save($invite);
	}

}

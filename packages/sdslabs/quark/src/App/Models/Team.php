<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Team extends Model
{
	protected $table = 'teams';
	protected $fillable = ['name', 'score'];
	protected $hidden = ['id', 'competition_id', 'owner_id', 'created_at', 'updated_at', 'pivot', 'score_updated_at'];
	protected $appends = ['rank'];

	public function getRouteKeyName()
	{
		return 'name';
	}

	public function competition()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Competition', 'competition_id');
	}

	public function owner()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\User', 'owner_id');
	}

	public function members()
	{
		return $this->belongsToMany('SDSLabs\Quark\App\Models\User', 'user_team_maps', 'team_id', 'user_id');
	}

	public function submissions()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\CompetitionSubmission');
	}

	public function invites()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Invite');
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
		return $this->user_invites()->attach($user, ['token' => $token, 'status' => 1]);
	}

}

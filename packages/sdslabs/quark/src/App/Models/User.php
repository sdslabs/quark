<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
	protected $table = 'users';
	protected $fillable = ['username', 'fullname'];
	protected $hidden = ['id', 'user_id', 'provider', 'email', 'role', 'credentials', 'created_at', 'updated_at', 'pivot'];

	public function getRouteKeyName()
	{
		return 'username';
	}

	public function submissions()
	{
		return $this->belongsToMany('SDSLabs\Quark\App\Models\Problem', 'practice_logs', 'user_id', 'problem_id');
	}

	public function problems_created()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Problem', 'creator_id');
	}

	public function problems_uploaded()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Problem', 'uploader_id');
	}

	public function teams()
	{
		return $this->belongsToMany('SDSLabs\Quark\App\Models\Team', 'user_team_maps', 'user_id', 'team_id');
	}

	public function owned_teams()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Team', 'owner_id');
	}

	public function competitions()
	{
		return $this->teams()->with('competition')->get()->pluck('competition');
	}

	public function isInCompetition($competition_id)
	{
		return $this->teams()->where('competition_id', $competition_id)->count() > 0;
	}

	public function team_invites()
	{
		return $this->belongsToMany('SDSLabs\Quark\App\Models\Team', 'user_team_invites', 'user_id', 'team_id')->withPivot('status', 'token')->withTimestamps();
	}

	public function invites_received()
	{
		return $this->team_invites()->where('status', 1);
	}

	public function invites_sent()
	{
		return $this->team_invites()->where('status', 2);
	}

	public function isDeveloper()
	{
		return ($this->role === "developer");
	}

	public function getRankAttribute()
	{
		// TODO: Get from memcache!
		return 1;
	}

	public function invite(Team $team, $token)
	{
		return $this->team_invites()->attach($team, ['token' => $token, 'status'=> 2]);
	}

}

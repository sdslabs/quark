<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Team extends Model
{
	protected $table = 'teams';
	protected $fillable = ['name'];
	protected $hidden = ['id', 'competition_id', 'owner_id', 'created_at', 'updated_at', 'pivot'];
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

	public function user_invites()
	{
		return $this->belongsToMany('SDSLabs\Quark\App\Models\User', 'user_team_invites', 'team_id', 'user_id')->withPivot('status', 'token')->withTimestamps();
	}

	public function invites_sent()
	{
		return $this->user_invites()->where('status', 1);
	}

	public function invites_received()
	{
		return $this->user_invites()->where('status', 2);
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
		// To be fixed!!!
		return 1;
	}

	public function invite(User $user, $token)
	{
		return $this->user_invites()->attach($user, ['token' => $token, 'status' => 1]);
	}

}

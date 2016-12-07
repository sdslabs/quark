<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
	protected $table = 'users';
	protected $fillable = ['username', 'fullname'];
	protected $hidden = ['id', 'user_id', 'provider', 'email', 'role', 'credentials', 'created_at', 'updated_at', 'score_updated_at', 'pivot'];
	protected $appends = ['rank'];

	public function getRouteKeyName()
	{
		return 'username';
	}

	public function submissions()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\PracticeSubmission');
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

	public function invites()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Invite');
	}

	public function isDeveloper()
	{
		return ($this->role === "developer");
	}

	public function getRankAttribute()
	{
		$rank = DB::select('select count(*) + 1 as rank from users where score > :score1 or (score = :score2 and score_updated_at < :score_updated_at)', ['score1' => $this->score, 'score2' => $this->score, 'score_updated_at' => $this->score_updated_at])[0]->rank;
		return $rank;
	}

	public function invite(Team $team, $token)
	{
		return $this->team_invites()->attach($team, ['token' => $token, 'status'=> 2]);
	}

}

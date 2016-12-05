<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'fullname'];
    protected $hidden = ['id', 'user_id', 'provider', 'email', 'credentials', 'created_at', 'updated_at', 'pivot'];

	public function getRouteKeyName()
	{
		return 'username';
	}

    public function roles()
    {
    	return $this->belongsToMany('SDSLabs\Quark\App\Models\Role', 'user_role_maps', 'user_id', 'role_id');
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

    public function all_teams()
    {
        return $this->belongsToMany('SDSLabs\Quark\App\Models\Team', 'user_team_maps', 'user_id', 'team_id');
    }

    public function owned_teams()
    {
        return $this->hasMany('SDSLabs\Quark\App\Models\Team', 'owner_id');
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
        return !is_null($this->roles()->where("name", "developer")->first());
    }

    public function getRank()
    {
        return $this->newQuery()->where('score', '>', $this->score)->count()+1;
    }

}

<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
	protected $table = 'users';
	protected $fillable = ['user_id', 'provider', 'credentials', 'username', 'fullname', 'email', 'image', 'score'];

    public function roles()
    {
    	return $this->belongsToMany('SDSLabs\Quark\App\Models\Role', 'user_role_maps', 'user_id', 'role_id');
    }

    public function problems()
    {
        return [
            "solved" => $this->belongsToMany('SDSLabs\Quark\App\Models\Problem', 'practice_logs', 'user_id', 'problem_id'),
            "created" => $this->hasMany('SDSLabs\Quark\App\Models\Problem', 'creator_id'),
            "uploaded" => $this->hasMany('SDSLabs\Quark\App\Models\Problem', 'uploader_id')
        ];
    }

    public function teams()
    {
        return [
            "owned" => $this->hasMany('SDSLabs\Quark\App\Models\Team', 'owner_id'),
            "member" => $this->belongsToMany('SDSLabs\Quark\App\Models\Team', 'user_team_maps', 'user_id', 'team_id')
        ];
    }

    public function invites() {
        $team_invites = $this->belongsToMany('SDSLabs\Quark\App\Models\Team', 'user_team_invites', 'user_id', 'team_id')->withPivot('status', 'token')->withTimestamps();
        return [
            "sent" => $team_invites->where('status', 2),
            "received" => $team_invites->where('status', 1)
        ];
    }
}

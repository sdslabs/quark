<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
	protected $table = 'users';
	protected $fillable = ['user_id', 'provider', 'credentials', 'username', 'fullname', 'email', 'image', 'score'];

    public function role()
    {
    	return $this->belongsTo('SDSLabs\Quark\App\Models\Role', 'role_id');
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
            "all" => $this->belongsToMany('SDSLabs\Quark\App\Models\Team', 'user_team_maps', 'user_id', 'team_id');
        ];
    }
}

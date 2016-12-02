<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
	protected $table = 'teams';
	protected $fillable = ['name', 'score'];
    protected $hidden = ['id', 'competition_id', 'owner_id', 'created_at', 'updated_at', 'pivot'];
    protected $appends = ['rank'];

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

    public function competition_logs()
    {
        return $this->hasMany('SDSLabs\Quark\App\Models\CompetitionLog');
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

    public function hasMember($user_id)
    {
        return !is_null($this->members()->where('users.user_id', $user_id)->first());
    }

    public function getRankAttribute()
    {
        // To be fixed!!!

        $rank = 0;
        $comp = $this->competition()->first();
        $rank += $comp->teams()->where('score', '>=', $this->score)->count();
        return $rank+1;
    }

}

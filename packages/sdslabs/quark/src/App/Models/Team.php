<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Team extends Model
{
	protected $table = 'teams';
	protected $fillable = ['name', 'score'];

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

    public function competitionLogs()
    {
        return $this->hasMany('SDSLabs\Quark\App\Models\CompetitionLog');
    }

    public function invites()
    {
        $user_invites = $this->belongsToMany('SDSLabs\Quark\App\Models\User', 'user_team_invites', 'team_id', 'user_id')->withPivot('status', 'token')->withTimestamps();
        return [
            "sent" => $user_invites->where('status', 1),
            "received" => $user_invites->where('status', 2)
        ];
    }

}

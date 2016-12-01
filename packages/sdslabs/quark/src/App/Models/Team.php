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

    public function members() {
        return $this->belongsToMany('SDSLabs\Quark\App\Models\User', 'user_team_maps', 'team_id', 'user_id');
    }
}

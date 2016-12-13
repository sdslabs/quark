<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Invite extends Model
{
	protected $table = 'user_team_invites';
	protected $fillable = ['token', 'status'];
	protected $hidden = ['id', 'team_id', 'user_id', 'created_at', 'updated_at'];

	public function team()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Team', 'team_id');
	}

	public function user()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\User', 'user_id');
	}

}

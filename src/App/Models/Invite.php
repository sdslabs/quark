<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;


class Invite extends Model
{
	protected $table = 'user_team_invites';
	protected $fillable = ['token', 'status'];
	protected $hidden = ['id', 'team_id', 'user_id', 'created_at', 'updated_at'];

	public function team()
	{
		return $this->belongsTo(App::make(Team::class), 'team_id');
	}

	public function user()
	{
		return $this->belongsTo(App::make(User::class), 'user_id');
	}

}

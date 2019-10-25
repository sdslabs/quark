<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

/**
 * Model class used to interact with the table user_team_invites.  
 */
class Invite extends Model
{
	/** @var string $table The table associated with the model. */
	protected $table = 'user_team_invites';
	/** @var array $fillable The attributes that are mass assignable. */
	protected $fillable = ['token', 'status'];
	/** @var array $hidden The attributes that should be hidden for arrays. */
	protected $hidden = ['id', 'team_id', 'user_id', 'created_at', 'updated_at'];

	/**
     * Get the team of a invite.
     */
	public function team()
	{
		return $this->belongsTo(App::make(Team::class), 'team_id');
	}

	/**
     * Get the user of a invite.
     */
	public function user()
	{
		return $this->belongsTo(App::make(User::class), 'user_id');
	}

}

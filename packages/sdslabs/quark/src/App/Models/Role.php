<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
	protected $table = 'roles';
	protected $fillable = ['name', 'title', 'description'];
	protected $hidden = ['id', 'created_at', 'updated_at', 'pivot'];

    public function users()
    {
    	return $this->belongsToMany('SDSLabs\Quark\App\Models\User', 'user_role_maps', 'role_id', 'user_id');
    }
}

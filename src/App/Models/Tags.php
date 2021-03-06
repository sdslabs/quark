<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;


class Tags extends Model
{
	protected $table = 'tags';
	protected $fillable = ['name'];
	protected $hidden = ['id', 'created_at', 'updated_at'];

	public function getRouteKeyName()
	{
		return 'name';
	}

	public function problems() {
		return $this->belongsToMany(App::make(Problem::class), 'problem_tags', 'tag_id', 'problem_id');
	}

}

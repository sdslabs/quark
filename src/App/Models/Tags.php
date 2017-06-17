<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;


class Tags extends Model
{
	protected $table = 'tags';
	protected $fillable = ['tagname'];
	protected $hidden = ['id', 'created_at', 'updated_at'];

	public function problem() {
		return $this->belongsToMany(App::make(Problem::class), 'problem_tags', 'tag_id', 'problem_id');
	}

}

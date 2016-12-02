<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class ProblemType extends Model
{

	protected $table = 'problem_types';
	protected $fillable = ['name', 'title', 'description'];
	protected $hidden = ['id', 'created_at', 'updated_at'];

	public function problems()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Problem', 'problem_type_id');
	}

}

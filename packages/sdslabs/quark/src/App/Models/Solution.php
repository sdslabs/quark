<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Solution extends Model
{
	protected $table = 'solutions';
	protected $fillable = ['score', 'answer'];
	protected $hidden = ['id', 'problem_id', 'answer'];
	public $timestamps = false;

	public function problem()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Problem');
	}
}

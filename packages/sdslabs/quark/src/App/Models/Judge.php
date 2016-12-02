<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Judge extends Model
{

	protected $table = 'judges';
	protected $fillable = ['name', 'title', 'description'];
	protected $hidden = ['id', 'created_at', 'updated_at'];

	public function solutions()
	{
		return [
			"practice" => $this->hasMany('SDSLabs\Quark\App\Models\Solution', 'practice_judge_id'),
			"competition" => $this->hasMany('SDSLabs\Quark\App\Models\Solution', 'competition_judge_id')
		];
	}

}

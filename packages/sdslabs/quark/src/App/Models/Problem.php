<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Problem extends Model
{
    
	public function competition()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Competitions', 'competition_id');
	}

	public function solution()
	{
		return $this->belongsTo('SDSLabs\Quark\App\Models\Solution', 'solution_id');
	}

}

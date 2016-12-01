<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Solution extends Model
{
	protected $table = 'solutions';
	protected $fillable = ['practice_score', 'competition_score', 'solution'];

    public function problem()
    {
    	return $this->hasOne('SDSLabs\Quark\App\Models\Problem');
    }

    public function judge()
    {
    	return [
            "practice" => $this->belongsTo('SDSLabs\Quark\App\Models\Judge', 'practice_judge_id'),
            "competition" => $this->belongsTo('SDSLabs\Quark\App\Models\Judge', 'competition_judge_id')
        ];   
    }
}

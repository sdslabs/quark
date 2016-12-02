<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Solution extends Model
{
	protected $table = 'solutions';
	protected $fillable = ['practice_score', 'competition_score', 'solution'];
    protected $hidden = ['id', 'practice_judge_id', 'competition_judge_id', 'practice_score', 'competition_score', 'solution'];
    protected $appends = ['score', 'judge'];

    public function problem()
    {
    	return $this->hasOne('SDSLabs\Quark\App\Models\Problem');
    }

    public function competition_judge()
    {
        return $this->belongsTo('SDSLabs\Quark\App\Models\Judge', 'competition_judge_id');
    }

    public function practice_judge()
    {
        return $this->belongsTo('SDSLabs\Quark\App\Models\Judge', 'practice_judge_id');
    }

    public function getJudgeAttribute()
    {
    	if($this->problem()->first()->practice)
            return $this->practice_judge()->first();
        else
            return $this->competition_judge()->first();
    }

    public function getScoreAttribute()
    {
        if($this->problem()->first()->practice)
            return $this->practice_score;
        else
            return $this->competition_score;
    }
}

<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class PracticeLog extends Model
{
    protected $table = 'practice_logs';

    public function problem()
    {
    	return $this->belongsTo('SDSLabs\Quark\App\Models\Problem', 'problem_id');
    }

    public function competition()
    {
    	return $this->belongsTo('SDSLabs\Quark\App\Models\Competition', 'competition_id');
    }

    public function getTime()
    {
    	$dateTime = Carbon::createFromTimestamp($this->created_at->timestamp,'Asia/Kolkata');
    	return [$dateTime->format('h:i a'), $dateTime->toFormattedDateString()];
    }
}

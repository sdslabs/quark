<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class PracticeLog extends Model
{
    protected $table = 'practice_logs';
    protected $fillable = ['score'];

    public function problem()
    {
    	return $this->belongsTo('SDSLabs\Quark\App\Models\Problem', 'problem_id');
    }

    public function user()
    {
    	return $this->belongsTo('SDSLabs\Quark\App\Models\User', 'user_id');
    }

    public function getTime()
    {
    	$dateTime = Carbon::createFromTimestamp($this->created_at->timestamp,'Asia/Kolkata');
    	return [$dateTime->format('h:i a'), $dateTime->toFormattedDateString()];
    }
}

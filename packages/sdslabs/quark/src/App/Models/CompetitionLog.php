<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class CompetitionLog extends Model
{
    protected $table = 'competition_logs';
    protected $fillable = ['score'];
    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at', 'team_id', 'problem_id'];

    public function problem()
    {
    	return $this->belongsTo('SDSLabs\Quark\App\Models\Problem', 'problem_id');
    }

    public function team()
    {
    	return $this->belongsTo('SDSLabs\Quark\App\Models\Team', 'team_id');
    }

    public function getTime()
    {
    	$dateTime = Carbon::createFromTimestamp($this->created_at->timestamp,'Asia/Kolkata');
    	return [$dateTime->format('h:i a'), $dateTime->toFormattedDateString()];
    }
}

<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Competition extends Model
{
    
	public function problems()
	{
		return $this->hasMany('SDSLabs\Quark\App\Models\Problem');
	}

	public function getStartTimeAttribute()
	{
		$dateTime = Carbon::createFromTimestamp($this->start_at,'Asia/Kolkata');
		return [$dateTime->format('h:i a'), $dateTime->toFormattedDateString()];
	}

	public function getEndTimeAttribute()
	{
		$dateTime = Carbon::createFromTimestamp($this->end_at,'Asia/Kolkata');
		return [$dateTime->format('h:i a'), $dateTime->toFormattedDateString()];
	}

}

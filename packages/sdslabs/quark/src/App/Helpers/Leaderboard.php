<?php

namespace SDSLabs\Quark\App\Helpers;

use Illuminate\Support\Facades\Redis;
use SDSLabs\Quark\App\Models\Competition;

class Leaderboard
{
	public static function competitionLeaderboard(\SDSLabs\Quark\App\Models\Competition $competition)
	{
		$teams = $competition->teams()->orderBy('score', 'desc');
		return $teams;
	}
}
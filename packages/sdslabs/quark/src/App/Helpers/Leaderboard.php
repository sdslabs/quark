<?php

namespace SDSLabs\Quark\App\Helpers;

use Illuminate\Support\Facades\Redis;
use SDSLabs\Quark\App\Models\Comeptition;

class Leaderboard
{
	public static function competitionLeaderboard(\SDSLabs\Quark\App\Models\Competition $competition, $limit = 50)
	{
		$teams = $competition->teams()->orderBy('score', 'desc')->paginate($limit, ['name', 'score']);
		return $teams;
	}
}
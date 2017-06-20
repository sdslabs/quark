<?php

namespace SDSLabs\Quark\App\Helpers;




class Leaderboard
{
	public static function competitionLeaderboard(\SDSLabs\Quark\App\Models\Competition $competition)
	{
		$teams = $competition->teams()->orderBy('score', 'desc')->orderBy('score_updated_at', 'asc')->paginate(30);
		return $teams;
	}
}

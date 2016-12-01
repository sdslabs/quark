<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use SDSLabs\Quark\App\Models\Competition;

class CompetitionController extends Controller
{
	public function index()
		{
			$now = time();
			$future_competitions = array();
			$running_competitions = array();
			$finished_competitions = array();

			$all_competitions = Competition::orderBy('created_at', 'DESC')->get();

			foreach($all_competitions as $competition)
			{
				if(($competition->start_at <= $now) && ($competition->end_at >= $now))
				{
					$competition->status = "Running";
					array_push($running_competitions, $competition);
				}
				else if($competition->end_at < $now)
				{
					$competition->status = "Finished";
					array_push($finished_competitions, $competition);
				}
				else
				{
					$competition->status = "Future";
					array_push($future_competitions, $competition);
				}
			}
			return view('competitions_index::pages/competition_index',
					[
						'competitions' => $all_competitions,
						'future_competitions' => $future_competitions,
						'running_competitions' => $running_competitions,
						'finished_competitions' => $finished_competitions
					]);
		}
}
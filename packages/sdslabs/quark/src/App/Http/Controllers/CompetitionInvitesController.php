<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Team;
use SDSLabs\Quark\App\Models\Competition;


class CompetitionInvitesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public static function findByName($name)
	{
		return Role::where("name", $name);
	}

	public function inviteUser(Competition $competition, $team_name , User $user)
	{
		$team = TeamController::findByName($team_name)->where('competition_id', $competition->id)->firstOrFail();

		if($competition->status === 'Finished')
			abort(403, "The competition has already ended.");

		if ($team->owner_id !== Auth::user()->id && !Auth::user()->isDeveloper())
			abort(403, "Only team owner can send invites.");

		if($team->members()->count() >= $competition->team_limit)
			abort(403, "Team is already full.");

    	if($user->competitions()->where('id', $competition->id)->count() > 0)
    		abort(403, "User is already participating is this competition.");

		if($team->invites_sent()->where('users.id', $user->id)->count() > 0)
			abort(403, "You've already sent an invite to this user.");

		if($team->invites_received()->where('users.id', $user->id)->count() > 0)
		{
			$team->addMember($user);
			abort(202, "You've accepted the invitation from user!");
		}

		$team->invite($user);
	}

	public function joinTeam(Competition $competition, $team_name)
	{
		$team = TeamController::findByName($team_name)->where('competition_id', $competition->id)->firstOrFail();

		if($competition->status === 'Finished')
			abort(403, "The competition has already ended");

		$user = Auth::user();

		if($user->competitions()->where('id', $competition->id)->count() > 0)
			abort(403, "You are already participating in this competition.");

		if($team->members()->count() >= $competition->team_limit)
			abort(403, "The team is already full");

		if($user->invites_received()->where('teams.id', $team->id)->count() > 0)
		{
			$team->addMember($user);
			abort(202, "You've accepted the invitation from team!");
		}

		$user->invite($team);
	}

	public function acceptInvite(Request $request)
	{
		$this->validate($request, [
			'token' => 'required'
		]);

		$token = $request->token;

		$team = Team::whereHas('user_invites', function($q) use($token) {
			$q->where('token', $token);
		})->with('competition')->first();

		if(is_null($team))
			return abort(404, "Invalid token!");

		$competition = $team->competition;

		if($competition->status === 'Finished')
			abort(403, "The competition has already ended");

		if($team->members()->count() >= $competition->team_limit)
			abort(403, "The team is already full");

		$user = $team->user_invites()->where('token', $token)->first();

		$invite_status = $user->pivot->status;

		if($invite_status === 0)
			abort(404, "The invite has already been accepted!");

		if($user->competitions()->where('id', $competition->id)->count() > 0)
		{
			if($invite_status === 1)
				abort(403, "You are already participating in this competition.");
			elseif($invite_status === 2)
				abort(403, "The user is already participating in this competition.");
		}

		if ($invite_status === 1 &&
				Auth::user()->id !== $user->id &&
				!Auth::user()->isDeveloper())
					abort(403, "The invite was not meant for you!");
		elseif ($invite_status === 2 &&
				Auth::user()->id !== $team->owner_id &&
				!Auth::user()->isDeveloper())
					abort(403, "Only team owner can accept the invite");

		$team->addMember($user);
		$user->pivot->update(['status' => 0]);
	}

	public function cancelInvite(Request $request)
	{
		$this->validate($request, [
			'token' => 'required'
		]);

		$token = $request->token;

		$team = Team::whereHas('user_invites', function($q) use($token) {
			$q->where('token', $token);
		})->with('competition')->first();

		if(is_null($team))
			abort(404, "Invalid token!");

		$competition = $team->competition;

		$user = $team->user_invites()->where('token', $token)->first();

		$invite_status = $user->pivot->status;

		if ($invite_status === 0)
			abort(403, "Invite has been accepted and cannot be cancelled.");

		elseif (($invite_status === 1 || $invite_status === 2) &&
				(Auth::user()->id === $user->id || Auth::user()->id === $team->owner_id || Auth::user()->isDeveloper()))
			$user->pivot->delete();

	}

}

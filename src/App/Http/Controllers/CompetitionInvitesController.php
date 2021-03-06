<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Competition;
use SDSLabs\Quark\App\Models\Invite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CompetitionInvitesController extends Controller
{
	public function __construct(Invite $invites, User $users)
	{
		$this->users = $users;
		$this->invites = $invites;
		$this->middleware('auth');
	}

	public function inviteUser(Competition $competition, $user)
	{
		$user = $this->users->where('username',$user)->first();

		if (!$user) {
			abort(404, "Please enter a valid username");
		}

		$team = Auth::user()->teams()->where('competition_id',$competition->id)->firstOrFail();
		if ($competition->status === 'Finished')
			abort(422, "The competition has already ended.");

		if ($team->owner_id !== Auth::user()->id && !Auth::user()->isDeveloper())
			abort(403, "Only team owner can send invites.");

		if ($team->members()->count() >= $competition->team_limit)
			abort(422, "Team is already full.");

		if ($user->isInCompetition($competition->id))
			abort(422, "User is already participating is this competition.");

		$invites = $this->invites->where('user_id', $user->id)->where('team_id', $team->id)->get();
		$invites_sent = $invites->where('status', 1);
		$invites_received = $invites->where('status', 2);

		if ($invites_sent->count() > 0)
			abort(422, "You've already sent an invite to this user.");

		if ($invites_received->count() > 0)
		{
			$team->addMember($user);
			$token = $invites_received[0]->token;
			$this->invites->where('token', $token)->update(['status' => 0]);
			abort(202, "You've accepted the invitation from user!");
		}

		$token = $this->generateToken();
		$team->invite($user, $token);

		return;
	}

	public function joinTeam(Competition $competition, $team_name)
	{
		$team = $competition->teams()->where('name', $team_name)->first();

		if (!$team) {
			abort(404, "Please enter a valid teamname");
		}

		if ($competition->status === 'Finished')
			abort(422, "The competition has already ended");

		$user = Auth::user();

		if ($user->isInCompetition($competition->id))
			abort(422, "You are already participating in this competition.");

		if ($team->members()->count() >= $competition->team_limit)
			abort(422, "The team is already full");

		$invites = $this->invites->where('team_id', $team->id)->where('user_id', $user->id)->get();
		$invites_sent = $invites->where('status', 2);
		$invites_received = $invites->where('status', 1);

		if($invites_sent->count() > 0)
			abort(422, "You've already sent an invite to the team");

		if ($invites_received->count() > 0)
		{
			$team->addMember($user);
			$token = $invites_received[0]->token;
			$this->invites->where('token', $token)->update(['status' => 0]);
			abort(202, "You've accepted the invitation from team!");
		}

		$token = $this->generateToken();
		$user->invite($team, $token);

		return;
	}

	public function acceptInvite(Request $request)
	{
		$this->validate($request, [
			'token' => 'required'
		]);

		$token = $request->token;

		$invite = $this->invites->where('token', $token)->first();
		if (is_null($invite))
			return abort(422, "Invalid token!");

		$team = $invite->team;

		$competition = $team->competition;

		if ($competition->status === 'Finished')
			abort(422, "The competition has already ended");

		if ($team->members()->count() >= $competition->team_limit)
			abort(422, "The team is already full");

		$user = $invite->user;

		if ($invite->status === 0)
			abort(422, "The invite has already been accepted!");

		if ($user->isInCompetition($competition->id))
		{
			if ($invite->status === 1)
				abort(422, "You are already participating in this competition.");
			elseif ($invite->status === 2)
				abort(422, "The user is already participating in this competition.");
		}

		if ($invite->status === 1 &&
			Auth::user()->id !== $user->id &&
			!Auth::user()->isDeveloper())
				abort(422, "The invite was not meant for you!");
		elseif ($invite->status === 2 &&
				Auth::user()->id !== $team->owner_id &&
				!Auth::user()->isDeveloper())
					abort(403, "Only team owner can accept the invite");

		$team->addMember($user);
		$invite->update(['status' => 0]);

		if ($team->members()->count() >= $competition->team_limit){
			$invites = $this->invites->where('team_id', '=', $team->id)->where(function ($query) {
				$query->where('status', '=', 1)
          		->orWhere('status', '=', 1);
          	})->get();

			foreach ($invites as $invite)
				$invite->delete();
		}

		return;
	}

	public function cancelInvite(Request $request)
	{
		$this->validate($request, [
			'token' => 'required'
		]);

		$token = $request->token;

		$invite = $this->invites->where('token', $token)->first();
		if (is_null($invite))
			abort(404, "Invalid token!");

		$team = $invite->team;

		$competition = $team->competition;

		$user = $invite->user;

		if ($invite->status === 0)
			abort(422, "Invite has been accepted and cannot be cancelled.");

		elseif (($invite->status === 1 || $invite->status === 2) &&
				(Auth::user()->id === $user->id || Auth::user()->id === $team->owner_id || Auth::user()->isDeveloper()))
			$invite->delete();

		return;

	}

	public function generateToken($len = 32)
	{
		$token = "";

		while(true)
		{
			$token = bin2hex(openssl_random_pseudo_bytes($len / 2));
			if ($this->invites->where('token', $token)->count() === 0)
				break;
		}

		return $token;
	}


}

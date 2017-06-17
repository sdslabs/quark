<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Problem;
use SDSLabs\Quark\App\Models\Competition;
use SDSLabs\Quark\App\Models\User;
use SDSLabs\Quark\App\Models\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class TagController extends Controller
{
	public function store(string $tagname, int  $problem_id) {
		if(Problem::where('id',$problem_id)->exists()) {
			$tag = Tags::where('tagname', $tagname)->first();
			if($tag) {
				$tag->problem()->attach($problem_id);
			}
			else {
				$tag = App::make(Tags::class, [[
				"tagname" => $tagname
			]]);
				$tag->save();
				$tag->problem()->attach($problem_id);
			}
		}
	}

	public function destroy(string $tagname, int  $problem_id) {
		if(Problem::where('id',$problem_id)->exists()) {
			$tag = Tags::where('tagname', $tagname)->first();
			if($tag) {
				$tag->problem()->detach($problem_id);
			}
		}
	}
	public function show(string $tagname) {
		$tag = Tags::where('tagname', $tagname)->first();
		if($tag) {
			return $tag->problem;
		}
	}
}

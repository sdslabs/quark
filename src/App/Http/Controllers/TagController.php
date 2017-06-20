<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Problem;
use SDSLabs\Quark\App\Models\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class TagController extends Controller
{

	public function __construct()
	{
		$this->middleware('developer')->except(['show']);
	}

	public function store(string $tagname, Problem $problem) {
		$tag = Tags::where('name', $tagname)->first();

		if($tag) {
			if(!($tag->problems()->where('problems.id', $problem->id)->exists()))
				$tag->problems()->attach($problem->id);
		}
		else {
			$tag = App::make(Tags::class, [[
				"name" => $tagname
			]]);
			$tag->save();
			$tag->problems()->attach($problem->id);
		}
	}

	public function destroy(string $tagname, Problem $problem) {
		$tag = Tags::where('name', $tagname)->firstOrFail();
		$tag->problems()->detach($problem->id);
	}

	public function show(string $tagname) {
		$tag = Tags::where('name', $tagname)->firstOrFail();
		return $tag->problems;
	}
}

<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use SDSLabs\Quark\App\Models\Problem;
use SDSLabs\Quark\App\Models\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;


class TagController extends Controller
{

	public function __construct()
	{
		$this->middleware('developer')->except(['show']);
	}

	public function store(string $tag, Problem $problem) {
		$name = $tag;
		$tag = Tags::where('name', $tag)->first();

		if($tag) {
			$tag->problems()->attach($problem->id);
		}
		else {
			$tag = App::make(Tags::class, [[
				"name" => $name
			]]);
			$tag->save();
			$tag->problems()->attach($problem->id);
		}
	}

	public function destroy(Tags $tag, Problem $problem) {
		$tag->problems()->detach($problem->id);
	}

	public function show(Tags $tag) {
		return $tag->problems;
	}
}

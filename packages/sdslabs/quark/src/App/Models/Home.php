<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


final class Home extends Model
{
	public static function home()
	{
		return "Works!!";
	}
}
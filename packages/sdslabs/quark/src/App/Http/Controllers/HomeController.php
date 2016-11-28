<?php

namespace SDSLabs\Quark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use SDSLabs\Quark\App\Models\Home;

class HomeController extends Controller
{
	public function index()
	{
		return Home::home();
	}
}
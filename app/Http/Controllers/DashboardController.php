<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends AdminController
{
	public function index()
	{
		return view( 'dashboard.index' );
	}
}

<?php

namespace App\Http\Controllers;

use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	public function index()
	{
		return ( Auth::check() || Auth::viaRemember() ) ? redirect( 'dashboard' ) : redirect( 'login' );
	}

	public function dashboard()
	{
		$user = Auth::user();

		switch ( $user->role_id ) {
			case UserRole::getAdministratorRole()->id:
				return response()->redirectToRoute( 'admin.dashboard' );
				break;
			case UserRole::getRole( 'group_manager' )->id:
				return response()->redirectToRoute( 'group.dashboard' );
				break;
			case UserRole::getRole( 'hotel_manager' )->id:
				return response()->redirectToRoute( 'hotel.dashboard' );
				break;
		}
	}
}

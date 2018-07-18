<?php

namespace App\Http\Controllers\Api;

use AlAdhanApi\HijriGregorianCalendar;
use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ApiResponse;
use Illuminate\Support\Facades\Config;

class EventController extends Controller
{
	public function now( Request $request )
	{
		$timezone = $request->get( 'timezone' );

		$calendar  = new HijriGregorianCalendar();
		$months    = $calendar->islamicMonths()['data'];
		$user_date = Carbon::now( $timezone )->format( "d-m-Y" );
		$date      = $calendar->gregorianToHijri( $user_date );

		if ( ! empty( $date['code'] ) && $date['code'] == 200 ) {
			$hijri  = $date['data']['hijri']['date'];
			$events = [];
			$date   = Carbon::parse( $hijri );
			$hijri  = $date->day . ' ' . $months[ $date->month ]['en'];

			Event::whereDay( 'hijri_date', $date->day )
			     ->whereMonth( 'hijri_date', $date->month )
			     ->each( function ( $event ) use ( &$timezone, &$events ) {
				     $events[] = $event;
			     } );

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), [
				'hijri_date' => $hijri,
				'date'       => $user_date,
				'events'     => $events
			] );
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ) );
	}
}

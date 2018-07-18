<?php

namespace App\Console\Commands;

use AlAdhanApi\HijriGregorianCalendar;
use App\Member;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CheckCalendar extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'calendar:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check calendar for updates';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		Member::where( 'timezone', '!=', '' )
		      ->select( 'timezone' )
		      ->distinct()
		      ->each( function ( $user ) {
			      $calendar  = new HijriGregorianCalendar();
			      $user_date = Carbon::now( $user->timezone )->format( "d-m-Y" );
			      $date      = $calendar->gregorianToHijri( $user_date );

			      if ( ! empty( $date['code'] ) && $date['code'] == 200 ) {
				      $hijri = $date['data']['hijri']['date'];

				      $this->call( 'event:notify', [
					      'timezone' => $user->timezone,
					      'date'     => $hijri
				      ] );
			      }
		      } );
	}
}

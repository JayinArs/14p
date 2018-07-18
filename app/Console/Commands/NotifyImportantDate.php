<?php

namespace App\Console\Commands;

use App\Event;
use App\Member;
use App\Notifications\ImportantDate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class NotifyImportantDate extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'event:notify {timezone} {date}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Notify events';

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
		$timezone = $this->argument( 'timezone' );
		$date     = $this->argument( 'date' );

		$date = Carbon::parse( $date );

		Event::whereDay( 'hijri_date', $date->day )
		     ->whereMonth( 'hijri_date', $date->month )
		     ->each( function ( $event ) use ( &$timezone ) {

			     $members = Member::where( 'timezone', $timezone );
			     $count   = $members->count();
			     $this->info( "Notified: {$event->title} to {$count} members" );
			     $important_date = new ImportantDate( $event );

			     $members->each( function ( $member ) use ( &$important_date ) {
				     $member->notify( $important_date );
			     } );
		     } );
	}
}

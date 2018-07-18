<?php

namespace App\Console;

use App\Console\Commands\CheckCalendar;
use App\Console\Commands\NotifyImportantDate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		CheckCalendar::class,
		NotifyImportantDate::class
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule( Schedule $schedule )
	{
		$schedule->command( 'calendar:check' );
	}

	/**
	 * Register the Closure based commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		require base_path( 'routes/console.php' );
	}
}

<?php

namespace App;

use AlAdhanApi\HijriGregorianCalendar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $fillable = [
		'title',
		'hijri_date',
		'description'
	];

	protected $appends = [
		'human_format'
	];

	public function getHumanFormatAttribute()
	{
		$calendar = new HijriGregorianCalendar();
		$months   = $calendar->islamicMonths()['data'];

		$hijri_date = Carbon::parse( $this->hijri_date );

		return $hijri_date->day . ' ' . $months[ $hijri_date->month ]['en'];
	}
}

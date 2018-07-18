<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuizRound extends Model
{
	public $timestamps = false;
	protected $fillable = [
		'quiz_id',
		'title',
		'type',
		'expiration_date',
		'start_date',
		'limit'
	];

	protected $appends = [
		'number_of_questions'
	];

	public function getNumberOfQuestionsAttribute()
	{
		return Question::where( 'quiz_round_id', $this->id )->count();
	}

	public function quiz()
	{
		return $this->belongsTo( 'App\Quiz' );
	}

	public function questions()
	{
		return $this->hasMany( 'App\Question', 'quiz_round_id' );
	}

	public function isStarted()
	{
		$start_date = Carbon::parse( $this->start_date );

		return Carbon::now()->diffInSeconds( $start_date, false ) <= 0;
	}

	public function isExpired()
	{
		$expiration_date = Carbon::parse( $this->expiration_date );

		return Carbon::now()->diffInSeconds( $expiration_date, false ) <= 0;
	}
}

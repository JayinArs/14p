<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Question extends Model
{
	public $timestamps = false;
	protected $fillable = [
		'question',
		'type',
		'quiz_round_id',
		'time'
	];

	protected $appends = [
		'type_name'
	];

	public function getTypeNameAttribute()
	{
		$types = Config::get( 'constants.question.type' );

		return $types[ $this->type ];
	}

	public function round()
	{
		return $this->belongsTo( 'App\QuizRound', 'quiz_round_id' );
	}

	public function answers()
	{
		return $this->hasMany( 'App\Answer', 'question_id' );
	}
}

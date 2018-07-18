<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
	protected $table = 'quiz';
	protected $fillable = [
		'title',
		'description',
		'status'
	];

	protected $appends = [
		'number_of_rounds',
		'number_of_participants'
	];

	public function getNumberOfRoundsAttribute()
	{
		return QuizRound::where( 'quiz_id', $this->id )->count();
	}

	public function getNumberOfParticipantsAttribute()
	{
		return Participant::where( 'quiz_id', $this->id )->count();
	}

	public function meta()
	{
		return $this->hasMany( 'App\QuizMeta' );
	}

	public function rounds()
	{
		return $this->hasMany( 'App\QuizRound' );
	}

	public function isParticipant( $member_id )
	{
		return Participant::where( 'member_id', $member_id )->where( 'quiz_id', $this->id )->exists();
	}
}

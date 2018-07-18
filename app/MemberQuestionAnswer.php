<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberQuestionAnswer extends Model
{
	protected $table = 'member_question_answer';
	protected $fillable = [
		'question_id',
		'member_id',
		'answer_id'
	];

	public function question()
	{
		return $this->belongsTo( 'App\Question' );
	}

	public function member()
	{
		return $this->belongsTo( 'App\Member' );
	}

	public function answer()
	{
		return $this->belongsTo( 'App\Answer' );
	}
}

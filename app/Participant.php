<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
	protected $table = 'participators';
	protected $fillable = [
		'quiz_id',
		'member_id'
	];

	public function member()
	{
		return $this->belongsTo( 'App\Member' );
	}

	public function quiz()
	{
		return $this->belongsTo( 'App\Quiz' );
	}
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizMeta extends Model
{
	public $timestamps = false;
	protected $fillable = [
		'meta_key',
		'meta_value',
		'quiz_id'
	];

	public function quiz()
	{
		return $this->belongsTo( 'App\Quiz' );
	}
}

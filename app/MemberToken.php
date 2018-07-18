<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberToken extends Model
{
	protected $fillable = [
		'token',
		'expiration',
		'is_expired',
		'member_id'
	];
	public $timestamps = false;

	public function member()
	{
		return $this->belongsTo( 'App\Member' );
	}
}

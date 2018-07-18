<?php

namespace App;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'username',
		'email',
		'role_id',
		'password'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	public function role()
	{
		return $this->belongsTo( 'App\UserRole' );
	}

	/**
	 * The channels the user receives notification broadcasts on.
	 *
	 * @return string
	 */
	public function receivesBroadcastNotificationsOn()
	{
		return 'user.' . $this->id;
	}
}

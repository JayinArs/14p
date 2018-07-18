<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Member extends Model
{
	use Notifiable;

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'password',
		'country',
		'phone',
		'city',
		'state',
		'street',
		'photo',
		'gender',
		'age_group',
		'login_type',
		'timezone',
		'guid'
	];

	protected $hidden = [
		'password'
	];

	public function routeNotificationForFirebase()
	{
		return [ $this->guid ];
	}

	public function receivesBroadcastNotificationsOn()
	{
		return 'member.' . $this->id;
	}
}

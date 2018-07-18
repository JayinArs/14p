<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
	protected $table = 'user_roles';
	protected $fillable = [
		'name'
	];

	public static function getAdministratorRole()
	{
		return UserRole::where( 'role', 'administrator' )->first();
	}

	public static function getRole( $name )
	{
		return UserRole::where( 'role', $name )->first();
	}
}

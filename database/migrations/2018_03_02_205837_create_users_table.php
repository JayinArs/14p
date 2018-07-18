<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'users', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'username' )->unique();
			$table->string( 'email' )->unique();
			$table->string( 'password' );
			$table->integer( 'role_id' )->unsigned();
			$table->rememberToken();
			$table->timestamps();

			$table->index( [ 'role_id' ], 'user_role' );
			$table->foreign( 'role_id' )->references( 'id' )->on( 'user_roles' )->onDelete( 'cascade' );
		} );

		DB::table( 'users' )->insert(
			[
				[
					'id'       => 1,
					'username' => 'administrator',
					'email'    => 'admin@jafferyforum.com',
					'password' => bcrypt( 'jafferyforum!' ),
					'role_id'  => \App\UserRole::getAdministratorRole()->id
				]
			]
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'users' );
	}
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'members', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'first_name' );
			$table->string( 'last_name' );
			$table->string( 'email' )->unique();
			$table->string( 'password' );
			$table->string( 'country' );
			$table->string( 'phone' )->nullable();
			$table->string( 'photo' )->nullable();
			$table->string( 'age_group' )->nullable();
			$table->string( 'gender' )->nullable();
			$table->string( 'city' )->nullable();
			$table->string( 'state' )->nullable();
			$table->string( 'street' )->nullable();
			$table->string( 'timezone' )->nullable();
			$table->string( 'login_type' )->nullable();
			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'members' );
	}
}

<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'user_roles', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'role' )->unique();
		} );

		DB::table( 'user_roles' )->insert(
			[
				[
					'id'   => 1,
					'role' => 'administrator'
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
		Schema::dropIfExists( 'user_roles' );
	}
}

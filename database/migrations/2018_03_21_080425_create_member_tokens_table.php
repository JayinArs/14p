<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTokensTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'member_tokens', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'token' );
			$table->dateTime( 'expiration' );
			$table->boolean( 'is_expired' )->default( false );
			$table->unsignedInteger( 'member_id' );

			$table->foreign( 'member_id' )->references( 'id' )->on( 'members' )->onDelete( 'cascade' );
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'member_tokens' );
	}
}

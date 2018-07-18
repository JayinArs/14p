<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizRoundsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'quiz_rounds', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'quiz_id' );
			$table->string( 'title' );
			$table->string( 'type' );
			$table->integer( 'limit' );
			$table->dateTime( 'start_date' )->nullable();
			$table->dateTime( 'expiration_date' )->nullable();

			$table->foreign( 'quiz_id' )->references( 'id' )->on( 'quiz' )->onDelete( 'cascade' );
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'quiz_rounds' );
	}
}

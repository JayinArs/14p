<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'questions', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'question' );
			$table->string( 'type' );
			$table->unsignedInteger( 'quiz_round_id' );
			$table->time( 'time' );

			$table->foreign( 'quiz_round_id' )->references( 'id' )->on( 'quiz_rounds' )->onDelete( 'cascade' );
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}
}

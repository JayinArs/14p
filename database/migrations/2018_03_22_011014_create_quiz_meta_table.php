<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizMetaTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'quiz_meta', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'meta_key' );
			$table->string( 'meta_value' );
			$table->unsignedInteger( 'quiz_id' );

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
		Schema::dropIfExists( 'quiz_meta' );
	}
}

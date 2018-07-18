<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipatorsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'participators', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'quiz_id' );
			$table->unsignedInteger( 'member_id' );
			$table->timestamps();

			$table->foreign( 'quiz_id' )->references( 'id' )->on( 'quiz' )->onDelete( 'cascade' );
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
		Schema::dropIfExists( 'participators' );
	}
}

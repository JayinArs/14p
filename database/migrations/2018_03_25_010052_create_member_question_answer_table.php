<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberQuestionAnswerTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'member_question_answer', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'question_id' );
			$table->unsignedInteger( 'member_id' );
			$table->unsignedInteger( 'answer_id' );
			$table->timestamps();

			$table->foreign( 'question_id' )->references( 'id' )->on( 'questions' )->onDelete( 'cascade' );
			$table->foreign( 'member_id' )->references( 'id' )->on( 'members' )->onDelete( 'cascade' );
			$table->foreign( 'answer_id' )->references( 'id' )->on( 'answers' )->onDelete( 'cascade' );
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'member_question_answer' );
	}
}

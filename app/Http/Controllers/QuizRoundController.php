<?php

namespace App\Http\Controllers;

use App\Quiz;
use App\QuizRound;
use Illuminate\Http\Request;
use Datatables;
use Validator;

class QuizRoundController extends AdminController
{
	public function data( $quiz_id )
	{
		$quiz = Quiz::find( $quiz_id );

		return Datatables::of( $quiz->rounds )->make( true );
	}

	public function store( Request $request )
	{
		$data  = $request->only( [ 'title', 'type', 'limit', 'start_date', 'expiration_date', 'quiz_id' ] );
		$rules = [
			'title'           => 'required',
			'type'            => 'required',
			'limit'           => 'required|numeric',
			'start_date'      => 'required',
			'expiration_date' => 'required',
			'quiz_id'         => 'required|exists:quiz,id'
		];

		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			return response()->json( [
				                         'status' => 'danger',
				                         'errors' => $validator->getMessageBag()->toArray(),
			                         ], 400 );
		}

		$round = QuizRound::create( $data );

		if ( $round->id > 0 ) {
			return response()->json( [
				                         'status'  => 'success',
				                         'message' => 'Quiz round created successfully'
			                         ] );
		}

		return response()->json( [
			                         'status'  => 'danger',
			                         'message' => 'Failed to create quiz round',
		                         ] );
	}

	public function edit( QuizRound $round )
	{
		return view( 'round.edit', [
			'round' => $round
		] );
	}

	public function update( QuizRound $round, Request $request )
	{
		$data  = $request->only( [ 'title', 'type', 'limit', 'start_date', 'expiration_date' ] );
		$rules = [
			'title'           => 'required',
			'type'            => 'required',
			'limit'           => 'required|numeric',
			'start_date'      => 'required',
			'expiration_date' => 'required'
		];

		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			return response()->json( [
				                         'status' => 'danger',
				                         'errors' => $validator->getMessageBag()->toArray(),
			                         ], 400 );
		}

		$round->fill( $data );

		if ( $round->save() ) {
			return response()->json( [
				                         'status'  => 'success',
				                         'message' => 'Quiz round updated successfully',
			                         ] );
		}

		return response()->json( [
			                         'status'  => 'danger',
			                         'message' => 'Failed to update quiz round',
		                         ] );
	}

	public function destroy( QuizRound $round, Request $request )
	{
		$round->delete();

		if ( $request->has( 'redirect' ) ) {
			return redirect( $request->get( 'redirect' ) );
		}

		return response()->redirectToRoute( 'round.index' );
	}
}

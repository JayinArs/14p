<?php

namespace App\Http\Controllers;

use App\Quiz;
use App\QuizRound;
use Illuminate\Http\Request;
use Datatables;
use Validator;

class QuizController extends AdminController
{
	public function index()
	{
		return view( 'quiz.index' );
	}

	public function data()
	{
		return Datatables::of( Quiz::all() )->make( true );
	}

	public function create()
	{
		return view( 'quiz.create' );
	}

	public function store( Request $request )
	{
		$data  = $request->only( [ 'title', 'description', 'status' ] );
		$rules = [
			'title' => 'required'
		];

		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			return response()->json( [
				                         'status' => 'danger',
				                         'errors' => $validator->getMessageBag()->toArray(),
			                         ], 400 );
		}

		$quiz = Quiz::create( $data );

		if ( $quiz->id > 0 ) {
			return response()->json( [
				                         'status'  => 'success',
				                         'message' => 'Quiz created successfully',
				                         'quiz'    => $quiz
			                         ] );
		}

		return response()->json( [
			                         'status'  => 'danger',
			                         'message' => 'Failed to create quiz',
		                         ] );
	}

	public function edit( Quiz $quiz )
	{
		return view( 'quiz.edit', [
			'quiz' => $quiz
		] );
	}

	public function update( Quiz $quiz, Request $request )
	{
		$data  = $request->only( [ 'title', 'description', 'status' ] );
		$rules = [
			'title' => 'required'
		];

		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			return response()->json( [
				                         'status' => 'danger',
				                         'errors' => $validator->getMessageBag()->toArray(),
			                         ], 400 );
		}

		$quiz->fill( $data );

		if ( $quiz->save() ) {
			return response()->json( [
				                         'status'  => 'success',
				                         'message' => 'Quiz updated successfully',
			                         ] );
		}

		return response()->json( [
			                         'status'  => 'danger',
			                         'message' => 'Failed to update quiz',
		                         ] );
	}

	public function destroy( Quiz $quiz )
	{
		$quiz->delete();

		return response()->redirectToRoute( 'quiz.index' );
	}
}

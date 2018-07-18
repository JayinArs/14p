<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\QuizRound;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Datatables;
use Validator;

class QuestionController extends Controller
{
	public function data( $round_id )
	{
		$round = QuizRound::find( $round_id );

		return Datatables::of( $round->questions )->make( true );
	}

	public function store( Request $request )
	{
		$data  = $request->only( [ 'question', 'quiz_round_id', 'time', 'type', 'choices' ] );
		$rules = [
			'question'      => 'required',
			'quiz_round_id' => 'required|exists:quiz_rounds,id',
			'type'          => 'required',
			'time'          => 'required|date_format:H:i:s',
			'choices'       => [ 'required_if:type,0', 'required_if:type,1' ]
		];

		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			return response()->json( [
				                         'status' => 'danger',
				                         'errors' => $validator->getMessageBag()->toArray(),
			                         ], 400 );
		}

		$data['time'] = Carbon::parse( $data['time'] )->toTimeString();
		$question     = Question::create( $data );

		if ( $question->id > 0 ) {

			if ( $question->type === '0' || $question->type === '1' ) {
				foreach ( $data['choices'] as $choice ) {
					Answer::create( [
						                'answer'      => $choice,
						                'question_id' => $question->id
					                ] );
				}
			}

			return response()->json( [
				                         'status'  => 'success',
				                         'message' => 'Question created successfully'
			                         ] );
		}

		return response()->json( [
			                         'status'  => 'danger',
			                         'message' => 'Failed to create question',
		                         ] );
	}

	public function edit( Question $question )
	{
		return view( 'question.edit', [
			'question' => $question
		] );
	}

	public function update( Question $question, Request $request )
	{
		$data  = $request->only( [ 'question', 'type', 'time', 'choices' ] );
		$rules = [
			'question' => 'required',
			'type'     => 'required',
			'time'     => 'required|date_format:H:i:s',
			'choices'  => [ 'required_if:type,0', 'required_if:type,1' ]
		];

		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			return response()->json( [
				                         'status' => 'danger',
				                         'errors' => $validator->getMessageBag()->toArray(),
			                         ], 400 );
		}

		$data['time'] = Carbon::parse( $data['time'] )->toTimeString();
		$choices      = $question->answers->toArray();
		$question->fill( $data );

		if ( $question->save() ) {

			if ( $question->type === '0' || $question->type === '1' ) {
				foreach ( $data['choices'] as $k => $choice ) {

					if ( empty( $choice ) ) {
						if ( isset( $choices[ $k ]['id'] ) ) {
							Answer::destroy( [ $choices[ $k ]['id'] ] );
						}
					} else {
						$ans = $choice;

						if ( isset( $choices[ $k ]['answer'] ) ) {
							$ans = $choices[ $k ]['answer'];
						}

						Answer::updateOrCreate( [
							                        'answer'      => $ans,
							                        'question_id' => $question->id
						                        ], [
							                        'answer' => $choice
						                        ] );
					}
				}
			} else {
				Answer::where( 'question_id', $question->id )->delete();
			}

			return response()->json( [
				                         'status'  => 'success',
				                         'message' => 'Question updated successfully',
			                         ] );
		}

		return response()->json( [
			                         'status'  => 'danger',
			                         'message' => 'Failed to update question',
		                         ] );
	}

	public function destroy( Question $question, Request $request )
	{
		$question->delete();

		if ( $request->has( 'redirect' ) ) {
			return redirect( $request->get( 'redirect' ) );
		}

		return response()->redirectToRoute( 'question.index' );
	}
}

<?php

namespace App\Http\Controllers\Api;

use App\Participant;
use App\Quiz;
use App\QuizRound;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ApiResponse;
use Illuminate\Support\Facades\Config;
use Validator;

class ParticipateController extends ApiController
{
	public function participate( $quiz, Request $request )
	{
		$data  = $request->only( [ 'member_id' ] );
		$rules = [
			'member_id' => 'required|exists:members,id'
		];

		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			$messages = $validator->messages()->all();

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, $messages[0] );
		} else {

			if ( $data['member_id'] != $request->header( 'MemberId' ) ) {
				return ApiResponse::create( Config::get( 'constants.HTTP_CODES.UNAUTHORIZED' ) );
			}

			$quiz = Quiz::find( $quiz );

			if ( $quiz->status == '1' ) {
				$data['quiz_id'] = $quiz->id;

				if ( ! $quiz->isParticipant( $data['member_id'] ) ) {
					$participant = Participant::create( $data );

					if ( $participant->id > 0 ) {
						return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), null, 'Participated successfully' );
					}
				} else {
					return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Already participated' );
				}
			} else {
				return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Quiz not active' );
			}
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ) );
	}
}

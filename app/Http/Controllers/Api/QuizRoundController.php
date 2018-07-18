<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Member;
use App\MemberQuestionAnswer;
use App\Question;
use App\QuizRound;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Validator;
use ApiResponse;

class QuizRoundController extends ApiController
{
	public function questions( $id, Request $request )
	{
		$rules = [
			'member_id' => 'required|exists:members,id'
		];

		$validator = Validator::make( $request->all(), $rules );

		if ( $validator->fails() ) {
			$messages = $validator->messages()->all();

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, $messages[0] );
		} else {
			$data = $request->only( [ 'member_id' ] );

			if ( $data['member_id'] != $request->header( 'MemberId' ) ) {
				return ApiResponse::create( Config::get( 'constants.HTTP_CODES.UNAUTHORIZED' ) );
			}

			$round          = QuizRound::with( 'quiz' )->find( $id );
			$is_quiz_active = $round && $round->quiz->status == '1';

			if ( $is_quiz_active && $round->isStarted() && ! $round->isExpired() && $round->quiz->isParticipant( $data['member_id'] ) ) {
				$questions = Question::with( [ 'answers' ] )
				                     ->where( 'quiz_round_id', $round->id )
				                     ->inRandomOrder()
				                     ->limit( $round->limit )
				                     ->get();

				return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), $questions );
			}
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Failed to get questions' );
	}

	public function answer( Request $request )
	{
		$rules = [
			'member_id'   => 'required|exists:members,id',
			'question_id' => 'required|exists:questions,id',
			'answer_id'   => 'required|exists:answers,id'
		];

		$validator = Validator::make( $request->all(), $rules );

		if ( $validator->fails() ) {
			$messages = $validator->messages()->all();

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, $messages[0] );
		} else {
			$data = $request->only( [ 'member_id', 'question_id', 'answer_id' ] );

			$member   = Member::find( $data['member_id'] );
			$question = Question::with( [ 'round.quiz' ] )->find( $data['question_id'] );
			$answer   = Answer::find( $data['answer_id'] );

			if ( $member && $question && $answer ) {
				$round          = $question->round;
				$is_quiz_active = $round && $round->quiz->status == '1';
				$is_same_round  = $answer->question->id == $question->id;
				$has_answered   = MemberQuestionAnswer::where( 'member_id', $data['member_id'] )
				                                      ->where( 'question_id', $data['question_id'] )
				                                      ->where( 'answer_id', $data['answer_id'] )
				                                      ->exists();

				if ( $is_quiz_active && $is_same_round && $round->isStarted() && ! $round->isExpired() ) {
					if ( $has_answered ) {
						return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Question already answered' );
					}

					MemberQuestionAnswer::create( $data );

					return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), null, 'Question answered successfully' );
				}
			}
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Failed to record answer' );
	}
}

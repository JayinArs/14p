<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Quiz;
use ApiResponse;
use App\QuizRound;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class QuizController extends Controller
{
	public function all()
	{
		$quizzes = Quiz::where( 'status', 1 )->get();

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), $quizzes );
	}

	public function get( $id )
	{
		$quiz = Quiz::find( $id );

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), $quiz );
	}

	public function recent()
	{
		$rounds = QuizRound::with( [ 'quiz' ] )
		                   ->orderBy( 'expiration_date', 'desc' )
		                   ->where( 'expiration_date', '<', Carbon::now()->toDateTimeString() )
		                   ->get();

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), $rounds );
	}
}

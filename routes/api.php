<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group( [ 'prefix' => 'v1' ], function () {
	/*
	 * Member Routes
	 */
	Route::post( 'register', 'Api\MemberAuthController@register' );
	Route::post( 'register_v2', 'Api\MemberAuthController@simple_register' );
	Route::post( 'login', 'Api\MemberAuthController@login' );
	Route::post( 'member/{member_id}/update', 'Api\MembersController@edit' );
	Route::post( 'member/{member_id}/update/password', 'Api\MembersController@update_password' );
	Route::get( 'member/{member_id}/detail', 'Api\MembersController@detail' );
	Route::get( 'member/{member_id}/quiz', 'Api\MembersController@quiz' );

	/*
	 * Event Routes
	 */
	Route::get( 'event/now', 'Api\EventController@now' );

	/*
	 * Quiz Routes
	 */
	Route::get( 'quiz', 'Api\QuizController@all' );
	Route::get( 'quiz/recent', 'Api\QuizController@recent' );
	Route::get( 'quiz/{quiz}', 'Api\QuizController@get' );

	/*
	 * Round Routes
	 */
	Route::get( 'round/{round}/questions', 'Api\QuizRoundController@questions' );
	Route::post( 'question/answer', 'Api\QuizRoundController@answer' );

	/*
	 * Participate Routes
	 */
	Route::post( 'quiz/{quiz}/participate', 'Api\ParticipateController@participate' );
} );
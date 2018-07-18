<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get( '/register', function () {
	return response()->redirectToRoute( 'dashboard' );
} );

Route::get( '/', function () {
	return response()->redirectToRoute( 'dashboard' );
} );

Route::get( 'dashboard', 'DashboardController@index' )->name( 'dashboard' );
/*
 * Member Routes
 */
Route::get( 'member/data', 'MemberController@data' )->name( 'member.data' );
Route::resource( 'member', 'MemberController', [
	'only' => [ 'index', 'edit', 'update', 'destroy' ]
] );
/*
 * Quiz Routes
 */
Route::get( 'quiz/data', 'QuizController@data' )->name( 'quiz.data' );
Route::resource( 'quiz', 'QuizController', [
	'only' => [ 'index', 'create', 'store', 'edit', 'update', 'destroy' ]
] );
/*
 * Quiz Round Routes
 */
Route::get( 'quiz/{quiz}/round/data', 'QuizRoundController@data' )->name( 'round.data' );
Route::resource( 'round', 'QuizRoundController', [
	'only' => [ 'store', 'update', 'edit', 'destroy' ]
] );
/*
 * Quiz Round Questions Routes
 */
Route::get( 'round/{round}/questions/data', 'QuestionController@data' )->name( 'question.data' );
Route::resource( 'question', 'QuestionController', [
	'only' => [ 'store', 'update', 'edit', 'destroy' ]
] );
<?php

namespace App\Http\Controllers\Api;

use App\Member;
use App\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Validator;
use ApiResponse;

class MembersController extends ApiController
{
	public function detail( $id )
	{
		$member = Member::find( $id );

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), [
			'member' => $member
		] );
	}

	public function edit( $id, Request $request )
	{
		$member = Member::find( $id );

		if ( $id != $request->header( 'MemberId' ) ) {
			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.UNAUTHORIZED' ) );
		}

		$rules = [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|unique:members,email,' . $id . ',id',
			'country'    => 'required',
			'phone'      => 'required',
			'city'       => 'required_without:state',
			'state'      => 'required_without:city',
			'timezone'   => 'required',
			'photo'      => 'image|max:3072'
		];

		$data      = $request->all();
		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			$messages = $validator->messages()->all();

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, $messages[0] );
		}

		$data = $request->only( [
			                        'first_name',
			                        'last_name',
			                        'email',
			                        'country',
			                        'phone',
			                        'city',
			                        'state',
			                        'street',
			                        'timezone'
		                        ] );

		if ( $request->hasFile( 'photo' ) ) {
			$file          = $request->file( 'photo' );
			$path          = $file->storeAs( 'photos', "member-{$member->id}_photo.{$file->clientExtension()}", 'public' );
			$data['photo'] = 'storage/' . $path;
		}

		$member->fill( $data );

		if ( $member->save() ) {
			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), null, 'Profile updated successfully' );
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Failed to update' );
	}

	public function update_password( $id, Request $request )
	{
		$member = Member::find( $id );

		if ( $id != $request->header( 'MemberId' ) ) {
			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.UNAUTHORIZED' ) );
		}

		$rules = [
			'password' => 'required|min:6',
		];

		$data      = $request->all();
		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			$messages = $validator->messages()->all();

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, $messages[0] );
		}

		$data = $request->only( [
			                        'password'
		                        ] );
		$member->fill( $data );

		if ( $member->save() ) {
			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), null, 'Password updated successfully' );
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Failed to update password' );
	}

	public function quiz( $id )
	{
		$quizzes = [];

		Participant::with( [ 'quiz' ] )
		           ->where( 'member_id', $id )
		           ->each( function ( $participate ) use ( &$quizzes ) {
			           $quizzes[ $participate->quiz->id ] = $participate->quiz;
		           } );

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), array_values( $quizzes ) );
	}
}

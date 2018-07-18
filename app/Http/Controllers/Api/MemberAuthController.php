<?php

namespace App\Http\Controllers\Api;

use App\Member;
use App\MemberToken;
use App\Notifications\NewMemberRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Validator;
use ApiResponse;

class MemberAuthController extends Controller
{
	private $social_media_auth = [ 'facebook' ];

	public function login( Request $request )
	{
		$rules = [
			'email'      => 'required|exists:members,email',
			'password'   => 'required_without:login_type',
			'login_type' => 'required_without:password',
			'auth_key'   => 'required_with:login_type'
		];

		$data      = $request->all();
		$validator = Validator::make( $data, $rules );

		if ( $validator->fails() ) {
			$messages = $validator->messages()->all();

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, $messages[0] );
		}

		$email    = $request->get( 'email' );
		$password = $request->get( 'password' );

		if ( $request->has( 'login_type' ) && in_array( $data['login_type'], $this->social_media_auth ) ) {
			$password = $request->get( 'auth_key' );
		}

		$member   = Member::where( 'email', $email );
		$is_valid = false;

		if ( $member->exists() ) {
			$member   = $member->first();
			$is_valid = Hash::check( $password, $member->password );
		}

		if ( $is_valid ) {
			$token = str_random( 60 );

			MemberToken::create( [
				                     'token'      => $token,
				                     'expiration' => Carbon::now()->addHour()->toDateTimeString(),
				                     'is_expired' => false,
				                     'member_id'  => $member->id
			                     ] );

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), [
				'token' => $token
			] );
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Failed to login' );
	}

	public function register( Request $request )
	{
		$rules = [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|unique:members,email',
			'password'   => 'required_without:login_type|min:6|confirmed',
			'country'    => 'required',
			//'phone'      => 'required_without:login_type',
			'city'       => 'required_without:state',
			'state'      => 'required_without:city',
			'guid'       => 'required',
			'timezone'   => 'required',
			'auth_key'   => 'required_with:login_type'
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
			                        'age_group',
			                        'gender',
			                        'country',
			                        'phone',
			                        'city',
			                        'state',
			                        'street',
			                        'timezone',
			                        'login_type',
			                        'guid'
		                        ] );

		if ( ! in_array( $data['login_type'], $this->social_media_auth ) ) {
			$data['password'] = bcrypt( $request->get( 'password' ) );
		} else {
			$data['password'] = bcrypt( $request->get( 'auth_key' ) );
		}

		$member = Member::create( $data );

		if ( $member->id > 0 ) {
			$token = str_random( 60 );

			MemberToken::create( [
				                     'token'      => $token,
				                     'expiration' => Carbon::now()->addHour()->toDateTimeString(),
				                     'is_expired' => false,
				                     'member_id'  => $member->id
			                     ] );

			$member->notify( new NewMemberRegistration() );

			return ApiResponse::create( Config::get( 'constants.HTTP_CODES.SUCCESS' ), [
				'member' => $member,
				'token'  => $token
			] );
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.FAILED' ), null, 'Failed to register' );
	}
}

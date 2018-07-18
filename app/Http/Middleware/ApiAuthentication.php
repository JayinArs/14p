<?php

namespace App\Http\Middleware;

use App\MemberToken;
use Closure;
use ApiResponse;
use Illuminate\Support\Facades\Config;

class ApiAuthentication
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return mixed
	 */
	public function handle( $request, Closure $next )
	{
		if ( $request->hasHeader( 'Authorization' ) && $request->hasHeader( 'MemberId' ) ) {
			$authorization = $request->header( 'Authorization' );
			$member_id     = $request->header( 'MemberId' );

			$token = MemberToken::where( 'token', $authorization )->where( 'member_id', $member_id );

			if ( $token->exists() ) {
				$token = $token->first();

				if ( ! $token->is_expired ) {
					return $next( $request );
				}
			}
		}

		return ApiResponse::create( Config::get( 'constants.HTTP_CODES.UNAUTHORIZED' ) );
	}
}

<?php

namespace App\Helpers;

use App;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class ApiResponseHelper
{
	private $json;

	/**
	 * @param int $code
	 * @param null $data
	 * @param null $message
	 * @param array $extras
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function create( $code = 200, $data = null, $message = null, $extras = array() )
	{
		$this->json = [];

		if ( $data || ( $code == 200 && ! is_null( $data ) ) ) {
			$this->json['data'] = $data;
		}

		if ( $message ) {
			$this->json['message'] = $message;
		}

		if ( is_array( $extras ) && ! empty( $extras ) ) {
			$this->json = array_merge( $this->json, $extras );
		}

		$this->json['code'] = $code;

		if ( isset( $this->json["code"] ) ) {
			switch ( $this->json["code"] ) {
				case Config::get( 'constants.HTTP_CODES.SUCCESS' ):

					if ( ! isset( $this->json["status"] ) ) {
						$this->json["status"] = true;
					}

					break;

				case Config::get( 'constants.HTTP_CODES.UNAUTHORIZED' ):

					if ( ! isset( $this->json["status"] ) ) {
						$this->json["status"] = false;
					}

					if ( ! isset( $this->json["message"] ) ) {
						$this->json["message"] = "invalid authorization";
					}

					break;

				default:

					if ( ! isset( $this->json["status"] ) ) {
						$this->json["status"] = false;
					}

					break;
			}
		}

		return response()
			->json( $this->json )
			->header( 'Access-Control-Allow-Origin', '*' );
	}
}
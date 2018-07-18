<?php

namespace App\Providers;

use App\Helpers\ApiResponseHelper;
use App\Helpers\FormatHelper;
use App\Helpers\PermissionHelper;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use App\Helpers\DashboardHelper;
use App\Helpers\ElementHelper;
use App\Helpers\OptionHelper;
use App\Helpers\UtilsHelper;
use App;

class LaravelLoggerProxy
{
	public function log( $msg )
	{
		Log::info( $msg );
	}
}

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @param UrlGenerator $url
	 */
	public function boot( UrlGenerator $url )
	{
		$pusher = $this->app->make( 'pusher' );
		$pusher->set_logger( new LaravelLoggerProxy() );

		//$url->forceSchema('https');
	}

	/**
	 * Register any application services.
	 */
	public function register()
	{
		$this->app->singleton( 'element', function ( $app ) {
			$element = new ElementHelper();

			return $element;
		} );

		$this->app->singleton( 'dashboard', function ( $app ) {
			$dashboard = new DashboardHelper();

			return $dashboard;
		} );

		$this->app->singleton( 'api_response', function ( $app ) {
			$api_response = new ApiResponseHelper();

			return $api_response;
		} );
	}

	public function provides()
	{
		return [
			'element',
			'dashboard',
			'App\Helpers\ElementHelper',
			'App\Helpers\DashboardHelper',
			'App\Helpers\ApiResponseHelper',
		];
	}
}

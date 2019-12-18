<?php
/**
 * Rest Endpoint Abstraction
 * @author: Alex Standiford
 * @date  : 2019-07-29
 */


namespace Plugin_Name_Replace_Me\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Rest_Endpoint {

	private static $endpoints = [];

	const REST_NAMESPACE = 'plugin_name_replace_me/v1';

	public function __construct( $route, $methods = [ 'GET' ] ) {
		if ( empty( self::$endpoints ) ) {
			add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
		}

		$args = [
				'route' => $route,
				'args'  => [
						'methods'  => $methods,
						'callback' => [ $this, 'endpoint' ],
				],
		];

		self::$endpoints[ get_class( $this ) ] = $args;
	}

	/**
	 * Endpoint callback.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Rest_Request $request The request object.
	 * @return mixed
	 */
	abstract function endpoint( \WP_Rest_Request $request );

	/**
	 * Registers the endpoints
	 *
	 * @since 1.0.0
	 *
	 * return void
	 */
	public function register_endpoints() {
		foreach ( self::$endpoints as $endpoint ) {
			register_rest_route( self::REST_NAMESPACE, $endpoint['route'], $endpoint['args'] );
		}
	}
}
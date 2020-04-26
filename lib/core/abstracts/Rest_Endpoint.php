<?php
/**
 * Rest Endpoint Abstraction
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */

namespace Plugin_Name_Replace_Me\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Rest_Endpoint
 *
 * @since 1.0.0
 *
 * @package Plugin_Name_Replace_Me\Abstracts
 */
abstract class Rest_Endpoint {

	/**
	 * Registry of endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @var array list of registered endpoints.
	 */
	private static $endpoints = [];

	/**
	 * The REST API's namespace.
	 *
	 * @since 1.0.0
	 */
	const REST_NAMESPACE = 'plugin-name-replace-me/v1';

	/**
	 * Endpoint callback.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Rest_Request $request The request object.
	 * @return mixed the REST endpoint response.
	 */
	abstract function endpoint( \WP_Rest_Request $request );

	/**
	 * Rest_Endpoint constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $route The rest route URI
	 * @param array  $methods Optiona. Array of methods this route supports. Default GET
	 */
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
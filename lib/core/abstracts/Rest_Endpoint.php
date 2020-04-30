<?php
/**
 * Rest Endpoint Abstraction
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */

namespace Plugin_Name_Replace_Me\Core\Abstracts;

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
abstract class Rest_Endpoint extends Feature_Extension {

	/**
	 * The REST API's namespace.
	 *
	 * @since 1.0.0
	 */
	public $rest_namespace = 'plugin-name-replace-me/v1';

	public $route = '/';

	public $args = [ 'methods' => 'GET' ];

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
	 */
	public function __construct() {
		$this->args['callback'] = [ $this, 'endpoint' ];
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		add_action( 'rest_api_init', [ $this, 'register' ] );
	}

	/**
	 * Registers the endpoints
	 *
	 * @since 1.0.0
	 *
	 * return void
	 */
	public function register() {
		register_rest_route( $this->rest_namespace, $this->route, $this->args );
	}
}
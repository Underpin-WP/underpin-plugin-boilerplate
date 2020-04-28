<?php
/**
 * Rest Endpoint Registry
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;
use Plugin_Name_Replace_Me\Core\Abstracts\Rest_Endpoint;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Rest_Endpoints
 * Registry for Cron Jobs
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */

class Rest_Endpoints extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Plugin_Name_Replace_Me\Core\Abstracts\Rest_Endpoint';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		//$this->add( 'key','namespaced_class' );
	}

	/**
	 * @param string $key
	 * @return Rest_Endpoint|\WP_Error Script Resulting REST Endpoint class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
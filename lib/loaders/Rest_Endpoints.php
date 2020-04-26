<?php
/**
 * Rest Endpoint Registry
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Registries\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;

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

	public function __construct() {
		parent::__construct( 'Plugin_Name_Replace_Me\Abstracts\Rest_Endpoint' );
	}

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		//$this->add( 'key','namespaced_class' );
	}

}
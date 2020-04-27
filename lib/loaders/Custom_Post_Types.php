<?php
/**
 * Custom Post Type Registry
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Custom_Post_Type;
use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Custom_Post_Types
 * Registry for Custom Post Types
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */
class Custom_Post_Types extends Loader_Registry {

	public function __construct() {
		parent::__construct( 'Plugin_Name_Replace_Me\Core\Abstracts\Custom_Post_Type' );
	}

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
//		$this->add( 'post_type', 'Namespace\To\Class');
	}

	/**
	 * @param string $key
	 * @return Custom_Post_Type|\WP_Error Script Resulting REST Endpoint class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}

}
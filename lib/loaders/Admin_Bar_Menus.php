<?php
/**
 * Admin Bar Menu Registry
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Admin_Bar_Menu;
use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Bar_Menus
 * Registry for Admin Pages
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */
class Admin_Bar_Menus extends Loader_Registry {

	public function __construct() {
		parent::__construct( 'Plugin_Name_Replace_Me\Core\Abstracts\Admin_Bar_Menu' );
	}

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {

		// If WP_DEBUG is active, turn on the debug bar.
		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			$this->add( 'debug_bar', 'Plugin_Name_Replace_Me\Core\Utilities\Debug_Bar' );
		}

	}

	/**
	 * @param string $key
	 * @return Admin_Bar_Menu|\WP_Error Script Resulting admin page class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
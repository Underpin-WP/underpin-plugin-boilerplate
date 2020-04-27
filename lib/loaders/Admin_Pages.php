<?php
/**
 * Admin Pages
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Admin_Page;
use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Pages
 * Registry for Admin Pages
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */
class Admin_Pages extends Loader_Registry {

	public function __construct() {
		parent::__construct( 'Plugin_Name_Replace_Me\Core\Abstracts\Admin_Page' );
	}

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		$this->add( 'admin_page', 'Plugin_Name_Replace_Me\Admin_Page' );
	}

	/**
	 * @param string $key
	 * @return Admin_Page|\WP_Error Script Resulting admin page class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
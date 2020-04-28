<?php
/**
 * Shortcodes
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;
use Plugin_Name_Replace_Me\Core\Abstracts\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Shortcodes
 * Registry for Cron Jobs
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */

class Shortcodes extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Plugin_Name_Replace_Me\Core\Abstracts\Shortcode';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		//$this->add( 'key','namespaced_class' );
	}

	/**
	 * @param string $key
	 * @return Shortcode|\WP_Error Script Resulting shortcode class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
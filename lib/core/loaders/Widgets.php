<?php
/**
 * Widgets
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Core\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Widgets
 * Registry for Cron Jobs
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */
class Widgets extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = '\WP_Widget';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		//$this->add( 'key','namespaced_class' );
	}

	/**
	 * @inheritDoc
	 */
	public function add( $key, $value ) {
		$valid = $this->validate_item( $key, $value );

		if ( true === $valid ) {
			add_action( 'widgets_init', function() use ( $value ) {
				register_widget( $value );
			} );
		}
	}

	/**
	 * @param string $key
	 * @return \WP_Widget|\WP_Error Script Resulting WP_Widget class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}

}
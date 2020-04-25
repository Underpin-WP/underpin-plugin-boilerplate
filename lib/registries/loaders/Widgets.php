<?php
/**
 * Widgets
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Registries\Loaders;


use Plugin_Name_Replace_Me\Abstracts\Registries\Loader_Registry;

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

	public function __construct() {
		parent::__construct( '\WP_Widget' );
	}

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

}
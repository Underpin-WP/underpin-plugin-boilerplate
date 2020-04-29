<?php
/**
 * Loader Registry.
 * This is used any time a set of extended classes are registered, and instantiated once.
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts\Registries;

use Plugin_Name_Replace_Me\Core\Abstracts\Feature_Extension;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Registry.
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */
abstract class Loader_Registry extends Registry {

	/**
	 * The abstraction class name.
	 * This is used to validate that the items in this service locator are extended
	 * from the correct abstraction.
	 *
	 * @since 1.0.0
	 * @var string The name of the abstract class this service locator uses.
	 */
	protected $abstraction_class = '';

	/**
	 * Loader_Registry constructor.
	 *
	 */
	public function __construct() {
		parent::__construct( $this->get_registry_id() );
	}

	/**
	 * Gets the service locator ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string The registry ID for this service locator.
	 */
	protected function get_registry_id() {
		$class = explode( '\\', $this->abstraction_class );

		return strtolower( array_pop( $class ) );
	}

	/**
	 * @inheritDoc
	 */
	public function add( $key, $value ) {
		$valid = $this->validate_item( $key, $value );
		if ( true === $valid ) {
			$this[ $key ] = new $value;
		}

		// If this implements registry actions, go ahead and start those up, too.
		if ( $this->get( $key ) instanceof Feature_Extension ) {
			$this->get( $key )->do_actions();
		}

		return $valid;
	}

	/**
	 * @inheritDoc
	 */
	public function validate_item( $key, $value ) {
		if ( is_subclass_of( $value, $this->abstraction_class ) ) {
			return true;
		}

		return plugin_name_replace_me()->logger()->log_as_error(
			'error',
			'invalid_service_type',
			'The specified item could not be instantiated. Invalid instance type',
			$key,
			[ 'key' => $key, 'value' => $value, 'expects_type' => $this->abstraction_class ]
		);
	}

}
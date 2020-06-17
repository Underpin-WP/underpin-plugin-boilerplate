<?php
/**
 * Loader Registry.
 * This is used any time a set of extended classes are registered, and instantiated once.
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin\Abstracts\Registries;

use Underpin\Abstracts\Feature_Extension;
use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Registry.
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
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
			if ( is_string( $value ) ) {
				$this[ $key ] = new $value;
			} else {
				$this[ $key ] = $value;
			}
		}

		// If this implements registry actions, go ahead and start those up, too.
		if ( self::has_trait( 'Underpin\Traits\Feature_Extension', $this->get( $key ) ) ) {
			$this->get( $key )->do_actions();

			underpin()->logger()->log(
				'notice',
				'loader_actions_ran',
				'The actions for the ' . $this->registry_id . ' item called ' . $key . ' ran.',
				$this->registry_id,
				[ 'ref' => $this->registry_id, 'key' => $key, 'value' => $value ]
			);
		}

		return $valid;
	}

	/**
	 * Checks to see if the class, or any of its parents, uses the specified trait.
	 *
	 * @since 1.0.0
	 *
	 * @param string              $trait The trait to check for
	 * @param object|string|false $class The class to check.
	 * @return bool true if the class uses the specified trait, otherwise false.
	 */
	public static function has_trait( $trait, $class ) {

		if ( false === $class ) {
			return false;
		}

		$traits = class_uses( $class );

		if ( in_array( $trait, $traits ) ) {
			return true;
		}

		while( get_parent_class( $class ) ){
			$class = get_parent_class( $class );

			$has_trait = self::has_trait( $trait, $class );

			if ( true === $has_trait ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function validate_item( $key, $value ) {
		if ( is_subclass_of( $value, $this->abstraction_class ) || $value instanceof $this->abstraction_class ) {
			return true;
		}

		return underpin()->logger()->log_as_error(
			'error',
			'invalid_service_type',
			'The specified item could not be instantiated. Invalid instance type',
			[ 'ref' => $key, 'value' => $value, 'expects_type' => $this->abstraction_class ]
		);
	}
}
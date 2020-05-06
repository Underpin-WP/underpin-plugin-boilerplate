<?php
/**
 * Registry Class.
 * This is used any time a set of identical things are stored.
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin\Abstracts\Registries;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Registry.
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */
abstract class Registry extends \ArrayIterator {

	/**
	 * Unique identifier for this registry.
	 *
	 * @since 1.0.0
	 * @var string A unique identifier for this registry.
	 */
	protected $registry_id;

	/**
	 * Determines if this registry is extendable.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $is_extendable = false;

	/**
	 * A human-readable description of this event type.
	 * This is used in debug logs to make it easier to understand why this exists.
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * A human-readable name for this event type.
	 * This is used in debug logs to make it easier to understand what this is.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * Registry constructor.
	 *
	 * @param string $registry_id The registry ID.
	 */
	public function __construct( $registry_id ) {
		$this->registry_id   = (string) $registry_id;
		parent::__construct();
		$this->set_default_items();
		$this->set_extended_items();
	}

	/**
	 * Sets the default items for the registry.
	 */
	abstract protected function set_default_items();

	/**
	 * Makes it possible to set extended items for the current registry.
	 *
	 * @since 1.0.0
	 * @void
	 */
	protected function set_extended_items() {
		$extended_items = [];

		if ( true === $this->is_extendable ) {

			/**
			 * Makes it possible to add extended items to this registry.
			 *
			 * @since 1.0.0
			 * @param array $extended_items Array of extended items to register.
			 */
			$extended_items = apply_filters( "underpin/registry/{$this->registry_id}", [] );
		}

		// Loop through and set the items.
		foreach ( $extended_items as $key => $value ) {
			$this->add( $key, $value );
		}
	}

	/**
	 * Validates an item. This runs just before adding items to the registry.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key   The key to validate.
	 * @param mixed  $value The value to validate.
	 * @return true|\WP_Error true if the item is valid, WP_Error otherwise.
	 */
	abstract protected function validate_item( $key, $value );

	/**
	 * Adds an item to the registry
	 *
	 * @since 1.0.0
	 *
	 * @param string $key   The key to validate.
	 * @param mixed  $value The value to validate.
	 * @return true|\WP_Error true if the item is valid, WP_Error otherwise.
	 */
	public function add( $key, $value ) {
		$valid = $this->validate_item( $key, $value );

		if ( true === $valid ) {
			$this[$key] = $value;
		}

		return $valid;
	}

	/**
	 * Retrieves a registered item.
	 *
	 * @param string $key The identifier for the item.
	 * @return mixed the item value.
	 */
	public function get( $key ) {
		if ( isset( $this[ $key ] ) ) {
			return $this[$key];
		} else {
			return new \WP_Error( 'key_not_set', 'Specified key is not set.', [ 'key' => $key ] );
		}
	}

}
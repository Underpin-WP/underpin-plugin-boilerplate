<?php
/**
 * WordPress Option Abstraction
 *
 * @since   1.0.0
 * @package Lib\Core\Abstracts
 */


namespace Underpin\Factories;
use Underpin\Abstracts\Feature_Extension;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Option
 * WordPress Option Class
 *
 * @since   1.0.0
 * @package Lib\Core\Abstracts
 */
class Option extends Feature_Extension {

	protected $key = false;

	protected $default_value = [];

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * Option constructor.
	 *
	 * @param string $key           The option key
	 * @param string $description   A human-readable description of this option
	 * @param        $name          Human readable name.
	 * @param mixed  $default_value The default value to set for this setting
	 */
	public function __construct( $key, $description, $name, $default_value = [] ) {
		$this->key           = $key;
		$this->description   = $description;
		$this->name          = $name;
		$this->default_value = $default_value;
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		$this->add();
	}

	/**
	 * Adds the option.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function add() {
		return add_option( $this->key, $this->default_value );
	}

	/**
	 * Updates the option to the specified value.
	 *
	 * @since 1.0.0
	 *
	 * @param $value
	 * @return bool
	 */
	public function update( $value ) {
		return update_option( $this->key, $value );
	}

	/**
	 * Resets the setting to the default value.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function reset() {
		return $this->update( $this->default_value );
	}

	/**
	 * Deletes the option.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function delete() {
		return delete_option( $this->key );
	}

	/**
	 * Retrieves the option.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	public function get() {
		return get_option( $this->key, $this->default_value );
	}

	public function export() {
		$this->value = $this->get();

		return $this;
	}

	/**
	 * Plucks a single value from an array of options.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting The setting to retrieve
	 * @return mixed|\WP_Error The value if it is set, otherwise WP_Error.
	 */
	public function pluck( $setting ) {
		$settings = $this->get();

		if ( isset( $settings[ $setting ] ) ) {
			return $settings[ $setting ];
		}

		return new \WP_Error( 'setting_not_set', 'The provided setting ' . $setting . ' is not set in this option.' );
	}
}
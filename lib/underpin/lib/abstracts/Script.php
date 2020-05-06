<?php
/**
 * Registers Scripts to WordPress
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Script
 * Class Scripts
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */
abstract class Script extends Feature_Extension {

	/**
	 * The handle for this script.
	 *
	 * @since 1.0.0
	 * @var string the script handle.
	 */
	protected $handle;

	/**
	 * The version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $ver = false;

	/**
	 * The source url for this script.
	 *
	 * @since 1.0.0
	 * @var bool|string
	 */
	protected $src = false;

	/**
	 * The dependencies for this script.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $deps = [];

	/**
	 * If this script should be displayed in the footer.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $in_footer = false;


	/**
	 * Params to send to the script when it is enqueued.
	 *
	 * @since 1.0.0
	 *
	 * @var array Array of params.
	 */
	protected $localized_params = [];

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Callback to retrieve the localized parameters for this script.
	 * If this is empty, localize does not fire.
	 *
	 * @since 1.0.0
	 * @return array list of localized params as key => value pairs.
	 */
	public function get_localized_params() {
		return $this->localized_params;
	}

	public function set_param( $key, $value ) {
		$this->localized_params[ $key ] = $value;

		return true;
	}

	public function remove_param( $key ) {
		if ( isset( $this->localized_params[ $key ] ) ) {
			unset( $this->localized_params[ $key ] );
		}

		return true;
	}

	/**
	 * Localizes the script, if there are any arguments to pass.
	 *
	 * @since 1.0.0
	 */
	public function localize() {
		$localized_params = $this->get_localized_params();

		// If we actually have localized params, localize and enqueue.
		if ( ! empty( $localized_params ) ) {
			wp_localize_script( $this->handle, $this->handle, $localized_params );
		}
	}

	/**
	 * Registers this script.
	 * In-general, this should automatically run based on the contexts provided in the class.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		wp_register_script( $this->handle, $this->src, $this->deps, $this->ver, $this->in_footer );
	}

	/**
	 * Enqueues the script, and auto-localizes values if necessary.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		$this->localize();
		wp_enqueue_script( $this->handle );
	}

}
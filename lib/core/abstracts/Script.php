<?php
/**
 * Registers Scripts to WordPress
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Script
 * Class Scripts
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */
abstract class Script {

	/**
	 * The handle for this script.
	 *
	 * @since 1.0.0
	 * @var string the script handle.
	 */
	private $handle;

	public function __construct( $handle, $src = false, $deps = array(), $ver = false, $in_footer = false ) {
		$this->handle = $handle;

		if ( false === $ver ) {
			$ver = PLUGIN_NAME_REPLACE_ME_PLUGIN_VERSION;
		}

		if ( false === $src ) {
			$src = PLUGIN_NAME_REPLACE_ME_URL . 'assets/js/build/' . $handle . '.min.js';
		}

		wp_register_script( $handle, $src, $deps, $ver, $in_footer );
	}

	public function get_localized_params() {
		return [];
	}

	public function localize() {
		$localized_params = $this->get_localized_params();

		// If we actually have localized params, localize and enqueue.
		if ( ! empty( $localized_params ) ) {
			wp_localize_script( $this->handle, $this->handle, $localized_params );
		}
	}

	public function enqueue() {
		$this->localize();
		wp_enqueue_script( $this->handle );
	}

}
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
abstract class Style {

	/**
	 * The handle for this script.
	 *
	 * @since 1.0.0
	 * @var string the script handle.
	 */
	private $handle;

	public function __construct( $handle, $src = false, $deps = array(), $ver = false, $media = 'all' ) {
		$this->handle = $handle;

		if ( false === $ver ) {
			$ver = PLUGIN_NAME_REPLACE_ME_PLUGIN_VERSION;
		}

		if ( false === $src ) {
			$src = PLUGIN_NAME_REPLACE_ME_URL . 'assets/css/build/' . $handle . '.min.css';
		}

		wp_register_style( $handle, $src, $deps, $ver, $media );
	}

	public function enqueue() {
		wp_enqueue_style( $this->handle );
	}

}
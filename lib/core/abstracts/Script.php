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
	 * The page contexts in which this script should be registered.
	 * This determines where this script is registered.
	 *
	 * @var array list of contexts. Can contain any of the following: 'site', 'admin', 'author'
	 */
	protected $contexts = [];

	/**
	 * Script constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->ver = false === $this->ver ? PLUGIN_NAME_REPLACE_ME_PLUGIN_VERSION : $this->ver;
		$this->src = false === $this->src ? PLUGIN_NAME_REPLACE_ME_JS_URL . $this->handle . '.min.js' : $this->src;
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		if ( in_array( 'site', $this->contexts ) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'register' ] );
		}
		if ( in_array( 'admin', $this->contexts ) ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'register' ] );
		}
		if ( in_array( 'author', $this->contexts ) ) {
			add_action( 'author_enqueue_scripts', [ $this, 'register' ] );
		}
	}

	/**
	 * Callback to retrieve the localized parameters for this script.
	 * If this is empty, localize does not fire.
	 *
	 * @since 1.0.0
	 * @return array list of localized params as key => value pairs.
	 */
	public function get_localized_params() {
		return [];
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
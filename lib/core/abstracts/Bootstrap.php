<?php
/**
 * Plugin Bootstrap
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;

use Plugin_Name_Replace_Me\Loaders;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Bootstrap
 * Bootstrap Class
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */
abstract class Bootstrap {

	/**
	 * Houses all of the singleton classes used in this plugin.
	 * Not intended to be manipulated directly.
	 *
	 * @since 1.0.0
	 * @var array Array of class instance.
	 */
	private $class_registry = [];

	/**
	 * Base class instance.
	 *
	 * @since 1.0.0
	 * @var Bootstrap|null The one true instance of the Bootstrap
	 */
	protected static $instance = null;

	/**
	 * Set up classes that cannot be otherwise loaded via the autoloader.
	 *
	 * This is where you can add anything that needs "registered" to WordPress,
	 * such as shortcodes, rest endpoints, blocks, and cron jobs.
	 *
	 * @since 1.0.0
	 */
	abstract protected function _setup_classes();

	/**
	 * Fetches the specified class, and constructs the class if it hasn't been constructed yet.
	 *
	 * @since 1.0.0
	 *
	 * @param $class
	 * @return mixed
	 */
	protected function _get_class( $class ) {
		if ( ! isset( $this->class_registry[ $class ] ) ) {
			$this->class_registry[ $class ] = new $class;
		}

		return $this->class_registry[ $class ];
	}

	/**
	 * Sends a notice if the WordPress or PHP version are below the minimum requirement.
	 *
	 * @since 1.0.0
	 */
	public function below_version_notice() {
		global $wp_version;

		if ( version_compare( $wp_version, PLUGIN_NAME_REPLACE_ME_MINIMUM_WP_VERSION, '<' ) ) {
			echo '<div class="error">
							<p>' . __( sprintf( "Plugin Name Replace Me plugin is not activated. The plugin requires at least WordPress %s to function.", self::MINIMUM_WP_VERSION ), 'plugin-name-replace-me' ) . '</p>
						</div>';
		}

		if ( version_compare( phpversion(), PLUGIN_NAME_REPLACE_ME_MINIMUM_PHP_VERSION, '<' ) ) {
			echo '<div class="error">
							<p>' . __( sprintf( "Plugin Name Replace Me plugin is not activated. The plugin requires at least PHP %s to function.", self::MINIMUM_PHP_VERSION ), 'plugin-name-replace-me' ) . '</p>
						</div>';
		}
	}

	/**
	 * Registers the autoloader.
	 *
	 * @sicne 1.0.0
	 *
	 * @return bool|string
	 */
	protected function _setup_autoloader() {
		try{
			spl_autoload_register( function( $class ) {
				$class = explode( '\\', $class );

				if ( 'Plugin_Name_Replace_Me' === $class[0] ) {
					array_shift( $class );
				}

				$file_name = array_pop( $class );
				$directory = str_replace( '_', '-', strtolower( implode( DIRECTORY_SEPARATOR, $class ) ) );
				$file      = trailingslashit( PLUGIN_NAME_REPLACE_ME_ROOT_DIR ) . 'lib/' . $directory . '/' . $file_name . '.php';
				if ( file_exists( $file ) ) {
					require $file;

					return true;
				}

				return false;
			} );
		}catch( \Exception $e ){
			$this->logger()->log_exception( 'autoload_failed', $e );

			return $e->getMessage();
		}

		return false;
	}

	/**
	 * Fetches the Logger instance.
	 *
	 * @since 1.0.0
	 *
	 * @return \Plugin_Name_Replace_Me\Core\Abstracts\Logger
	 */
	public function logger() {
		// If the DFS monitor plugin is active, use the dfsm logger
		if ( function_exists( 'dfsm' ) ) {
			return $this->_get_class( '\Plugin_Name_Replace_Me\Core\Utilities\Enhanced_Logger' );

			// Otherwise use the built-in logger.
		} else {
			return $this->_get_class( '\Plugin_Name_Replace_Me\Core\Utilities\Basic_Logger' );
		}
	}

	/**
	 * Retrieves the scripts loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Scripts
	 */
	public function scripts() {
		return $this->_get_class( '\Plugin_Name_Replace_Me\Loaders\Scripts' );
	}

	/**
	 * Retrieves the Styles loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Styles
	 */
	public function styles() {
		return $this->_get_class( '\Plugin_Name_Replace_Me\Loaders\Styles' );
	}

	/**
	 * Checks if the PHP version meets the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 */
	public static function supports_php_version() {
		return version_compare( phpversion(), PLUGIN_NAME_REPLACE_ME_MINIMUM_PHP_VERSION, '>=' );
	}

	/**
	 * Checks if the WP version meets the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 */
	public static function supports_wp_version() {
		global $wp_version;

		return version_compare( $wp_version, PLUGIN_NAME_REPLACE_ME_MINIMUM_WP_VERSION, '>=' );
	}

	/**
	 * Checks if all minimum requirements are met.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 */
	public static function plugin_is_supported() {
		return self::supports_wp_version() && self::supports_php_version();
	}

	/**
	 * A set of actions that run when this plugin does not meet the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function unsupported_actions() {
		global $wp_version;

		self::$instance = new \WP_Error(
			'minimum_version_not_met',
			__( sprintf(
				"The Plugin Name Replace Me plugin requires at least WordPress %s, and PHP %s.",
				PLUGIN_NAME_REPLACE_ME_MINIMUM_WP_VERSION,
				PLUGIN_NAME_REPLACE_ME_MINIMUM_PHP_VERSION
			), 'plugin-name-replace-me' ),
			array( 'current_wp_version' => $wp_version, 'php_version' => phpversion() )
		);

		add_action( 'admin_notices', array( $this, 'below_version_notice' ) );
	}

	/**
	 * Actions that run when this plugin meets the specified minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function setup() {

		/**
		 * Fires just before the Plugin Name Replace Me plugin starts up.
		 *
		 * @since 1.0.0
		 */
		do_action( 'plugin_name_replace_me/before_setup' );

		// Manually include generic functions.
		require_once( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'lib/functions.php' );

		// Set up the autoloader for everything else.
		$this->_setup_autoloader();

		// Set up classes that register things.
		$this->_setup_classes();

		/**
		 * Fires just after the Plugin Name Replace Me is completely set-up.
		 *
		 * @since 1.0.0
		 */
		do_action( 'plugin_name_replace_me/after_setup' );
	}
}
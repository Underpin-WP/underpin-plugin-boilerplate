<?php
/*
Plugin Name:
Description:
Version: 1.0.0
Author:
Author URI:
*/

namespace PLUGIN_NAME_REPLACE_ME {

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class PLUGIN_NAME_REPLACE_ME {
		private static $instance = null;

		private function __construct() {
		}


		/**
		 * Fetches the specified class, and constructs the class if it hasn't been constructed yet.
		 *
		 * @since 1.0.0
		 *
		 * @param $class
		 * @return mixed
		 */
		private function _get_class( $class ) {
			$exploded_class = explode( '\\', $class );
			$variable       = strtolower( array_pop( $exploded_class ) );

			if ( ! $this->$variable ) {
				$class           = __NAMESPACE__ . '\\' . $class;
				$this->$variable = new $class;
			}

			return $this->$variable;
		}

		/**
		 * Fetches the Logger instance.
		 *
		 * @since 1.0.0
		 *
		 * @return Utilities\Logger
		 */
		public function logger() {
			return $this->_get_class( 'Utilities\\Logger' );
		}

		/**
		 * Fires up the plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return self
		 */
		public static function init() {
			if ( ! isset( self::$instance ) ) {
				do_action( 'before_plugin_name_replace_me_setup' );
				self::$instance = new self;
				self::$instance->_define_constants();
				require_once( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'lib/functions.php' );
				require_once( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'lib/actions.php' );
				self::$instance->_register_scripts();
				self::$instance->_setup_autoloader();
				self::$instance->_setup_classes();
				do_action( 'after_plugin_name_replace_me_setup' );
			}

			return self::$instance;
		}

		/**
		 * Set up classes that cannot be otherwise loaded via the autoloader.
		 *
		 * This is where you can add anything that needs "registered" to WordPress,
		 * such as shortcodes, rest endpoints, blocks, and cron jobs.
		 *
		 * @since 1.0.0
		 */
		private function _setup_classes() {
			// REST Endpoints
			// new Rest\...

			// Cron Jobs Go Here
			new Cron\Purge_Logs;
			// new Cron\...

			// Shortcodes Go Here
			// new Shortcode\...

		}

		/**
		 * Registers styles and scripts.
		 *
		 * @since 1.0.0
		 */
		public function _register_scripts() {
			// wp_register_script...
		}

		/**
		 * Defines plugin-wide constants.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function _define_constants() {
			if ( ! defined( 'PLUGIN_NAME_REPLACE_ME_URL' ) ) {
				// Root URL for this plugin.
				define( 'PLUGIN_NAME_REPLACE_ME_URL', plugin_dir_url( __FILE__ ) );

				// Root directory for this plugin.
				define( 'PLUGIN_NAME_REPLACE_ME_ROOT_DIR', plugin_dir_path( __FILE__ ) );

				// Root file for this plugin. Used in activation hooks.
				define( 'PLUGIN_NAME_REPLACE_ME_ROOT_FILE', __FILE__ );

				// The URL to the assets directory. Use when registering scripts and styles.
				define( 'PLUGIN_NAME_REPLACE_ME_ASSETS_URL', PLUGIN_NAME_REPLACE_ME_URL . 'assets/' );

				// The template directory. Used by the template loader to determine where templates are stored.
				define( 'PLUGIN_NAME_REPLACE_ME_TEMPLATE_DIR', PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'templates/' );

				// Text domain for translation strings.
				define( 'PLUGIN_NAME_REPLACE_ME_TEXT_DOMAIN', 'plugin_name_replace_me' );

				// The version of this plugin. Use when registering scripts and styles to bust cache.
				define( 'PLUGIN_NAME_REPLACE_ME_VERSION', '1.0.0' );
			}
		}


		/**
		 * Registers the autoloader.
		 *
		 * @sicne 1.0.0
		 *
		 * @return bool|string
		 */
		private function _setup_autoloader() {
			try{
				spl_autoload_register( function( $class ) {
					$class = explode( '\\', $class );

					if ( __NAMESPACE__ === $class[0] ) {
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
				$this->logger()->error( 'autoload_failed', "Failed to autoload file. Error Message:", $e->getMessage() );

				return $e->getMessage();
			}

			return false;
		}
	}
}

namespace {

	use PLUGIN_NAME_REPLACE_ME\PLUGIN_NAME_REPLACE_ME;

	/**
	 * Fetches the instance
	 *
	 * @since 1.0.0
	 *
	 * @return PLUGIN_NAME_REPLACE_ME
	 */
	function plugin_name_replace_me() {
		return PLUGIN_NAME_REPLACE_ME::init();
	}

	plugin_name_replace_me();
}
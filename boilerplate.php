<?php
/*
Plugin Name: Plugin Name Replace Me
Description: Plugin Description Replace Me
Version: 1.0.0
Author: DesignFrame Solutions
Text Domain: plugin_name_replace_me
Domain Path: /languages
Requires at least: 4.7
Requires PHP: 5.6
Author URI:
*/

namespace Plugin_Name_Replace_Me {

	use Plugin_Name_Replace_Me\Registries\Loaders;

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Plugin Name Replace Me Base Class
	 *
	 * @since 1.0.0
	 */
	final class Plugin_Name_Replace_Me {

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
		 * @var Plugin_Name_Replace_Me|null The one true instance of Plugin_Name_Replace_Me
		 */
		private static $instance = null;


		/**
		 * Fires up the plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return self
		 */
		public static function init() {
			if ( ! isset( self::$instance ) ) {
				global $wp_version;
				$supports_wp_version  = version_compare( $wp_version, '4.7', '>=' );
				$supports_php_version = version_compare( phpversion(), '5.6', '>=' );

				if ( $supports_wp_version && $supports_php_version ) {

					/**
					 * Fires just before the Plugin Name Replace Me plugin starts up.
					 *
					 * @since 1.0.0
					 */
					do_action( 'plugin_name_replace_me/before_setup' );

					self::$instance = new self;
					self::$instance->_define_constants();
					require_once( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'lib/functions.php' );
					self::$instance->_setup_autoloader();
					self::$instance->_setup_classes();

					/**
					 * Fires just after the Plugin Name Replace Me is completely set-up.
					 *
					 * @since 1.0.0
					 */
					do_action( 'plugin_name_replace_me/after_setup' );

					// If this installation is using the DFS Monitor plugin, register the custom events.
					if ( ! did_action( 'dfsm\after_setup' ) ) {
						add_action( 'dfsm\after_setup', [ self::$instance, 'setup_events' ] );
					} else {
						self::$instance->setup_events();
					}

				} else {
					$self           = new self;
					self::$instance = new \WP_Error(
						'minimum_version_not_met',
						__( "The Plugin Name Replace Me plugin requires at least WordPress 4.7, and PHP 5.6.", 'plugin-name-replace-me' ),
						array( 'current_wp_version' => $wp_version, 'php_version' => phpversion() )
					);

					add_action( 'admin_notices', array( $self, 'below_version_notice' ) );
				}
			}

			return self::$instance;
		}

		/**
		 * Fetches the Logger instance.
		 *
		 * @since 1.0.0
		 *
		 * @return Abstracts\Logger
		 */
		public function logger() {
			// If the DFS monitor plugin is active, use the dfsm logger
			if ( function_exists( 'dfsm' ) ) {
				return $this->_get_class( 'Plugin_Name_Replace_Me\\Utilities\\Enhanced_Logger' );

				// Otherwise use the built-in logger.
			} else {
				return $this->_get_class( 'Plugin_Name_Replace_Me\\Utilities\\Basic_Logger' );
			}
		}

		/**
		 * Retrieves the scripts loader.
		 *
		 * @since 1.0.0
		 *
		 * @return \Plugin_Name_Replace_Me\Registries\Loaders\Scripts
		 */
		public function scripts() {
			return $this->_get_class( '\Plugin_Name_Replace_Me\Registries\Loaders\Scripts' );
		}

		/**
		 * Retrieves the Styles loader.
		 *
		 * @since 1.0.0
		 *
		 * @return \Plugin_Name_Replace_Me\Registries\Loaders\Styles
		 */
		public function styles() {
			return $this->_get_class( '\Plugin_Name_Replace_Me\Registries\Loaders\Styles' );
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
			// Cron Job Registry
			new Loaders\Cron_Jobs;

			// Scripts
			$this->scripts();

			// Styles
			$this->styles();

			// REST Endpoints
			//new Loaders\Rest_Endpoints;

			// Shortcodes
			// new Loaders\Shortcodes;

			// Widgets
			// new Loaders\Widgets;
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
			if ( ! isset( $this->class_registry[ $class ] ) ) {
				$this->class_registry[ $class ] = new $class;
			}

			return $this->class_registry[ $class ];
		}


		/**
		 * Registers custom event log types for DFS Monitor Log, if the plugin is active.
		 *
		 * @since 1.0.0
		 */
		public function setup_events() {
			if ( class_exists( '\DFS_Monitor\Abstracts\Event_Log_Type' ) ) {
				new Utilities\Events\Plugin_Name_Replace_Me_Error;
			}
		}

		/**
		 * Sends a notice if the WordPress or PHP version are below the minimum requirement.
		 *
		 * @since 1.0.0
		 */
		public function below_version_notice() {
			global $wp_version;

			if ( version_compare( $wp_version, '4.7', '<' ) ) {
				echo '<div class="error">
							<p>' . __( "Plugin Name Replace Me plugin is not activated. The plugin requires at least WordPress 4.7 to function.", 'plugin-name-replace-me' ) . '</p>
						</div>';
			}

			if ( version_compare( phpversion(), '5.6', '<' ) ) {
				echo '<div class="error">
							<p>' . __( "Plugin Name Replace Me plugin is not activated. The plugin requires at least PHP 5.6 to function.", 'plugin-name-replace-me' ) . '</p>
						</div>';
			}
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

				// The template directory. Used by the template loader to determine where templates are stored.
				define( 'PLUGIN_NAME_REPLACE_ME_TEMPLATE_DIR', PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'templates/' );

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
				$this->logger()->log_exception( 'autoload_failed', $e );

				return $e->getMessage();
			}

			return false;
		}
	}
}

namespace {

	use Plugin_Name_Replace_Me\Plugin_Name_Replace_Me;

	/**
	 * Fetches the instance
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin_Name_Replace_Me
	 */
	function plugin_name_replace_me() {
		return Plugin_Name_Replace_Me::init();
	}

	plugin_name_replace_me();
}
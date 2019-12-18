<?php
/*
Plugin Name:
Description:
Version: 1.0.0
Author:
Text Domain: plugin_name_replace_me
Domain Path: /languages
Requires at least: 4.7
Requires PHP: 5.6
Author URI:
*/

namespace Plugin_Name_Replace_Me {

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	final class Plugin_Name_Replace_Me {
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
		 * @return Abstracts\Logger
		 */
		public function logger() {
			// If the DFS monitor plugin is active, use the dfsm logger
			if ( function_exists( 'dfsm' ) ) {
				return $this->_get_class( 'Utilities\\Enhanced_Logger' );

				// Otherwise use the built-in logger.
			} else {
				return $this->_get_class( 'Utilities\\Basic_Logger' );
			}
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
				global $wp_version;
				$supports_wp_version  = version_compare( $wp_version, '4.7', '>=' );
				$supports_php_version = version_compare( phpversion(), '5.6', '>=' );

				if ( $supports_wp_version && $supports_php_version ) {

					/**
					 * Fires just before the plugin name replace me plugin starts up.
					 *
					 * @since 1.0.0
					 */
					do_action( 'plugin_name_replace_me/before_setup' );

					self::$instance = new self;
					self::$instance->_define_constants();
					require_once( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'lib/functions.php' );
					require_once( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'lib/actions.php' );
					self::$instance->_register_scripts();
					self::$instance->_setup_autoloader();
					self::$instance->_setup_classes();

					/**
					 * Fires just after the plugin name replace me is completely set-up.
					 *
					 * @since 1.0.0
					 */
					do_action( 'plugin_name_replace_me/after_setup' );

					// If this installation is using the DFS Monitor plugin, register the custom events.
					add_action( 'dfsm\after_setup', function() {
						new Utilities\Events\Plugin_Name_Replace_Me_Error;
					} );

				} else {
					$self           = new self;
					self::$instance = new \WP_Error(
						'minimum_version_not_met',
						__( "The plugin name replace me plugin requires at least WordPress 4.7, and PHP 5.6." ),
						array( 'current_wp_version' => $wp_version, 'php_version' => phpversion() )
					);

					add_action( 'admin_notices', array( $self, 'below_version_notice' ) );
				}
			}

			return self::$instance;
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
							<p>' . __( "plugin name replace me plugin is not activated. The plugin requires at least WordPress 4.7 to function." ) . '</p>
						</div>';
			}

			if ( version_compare( phpversion(), '5.6', '<' ) ) {
				echo '<div class="error">
							<p>' . __( "plugin name replace me plugin is not activated. The plugin requires at least PHP 5.6 to function." ) . '</p>
						</div>';
			}
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

					if ( __NAMESPACE__ === $class[ 0 ] ) {
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
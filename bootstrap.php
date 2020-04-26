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

	use Plugin_Name_Replace_Me\Core\Traits\Bootstrap;
	use Plugin_Name_Replace_Me\Loaders;

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Plugin Name Replace Me Base Class
	 *
	 * @since 1.0.0
	 */
	final class Plugin_Name_Replace_Me {
		use Bootstrap;

		/**
		 * Set up classes that cannot be otherwise loaded via the autoloader.
		 *
		 * This is where you can add anything that needs "registered" to WordPress,
		 * such as shortcodes, rest endpoints, blocks, and cron jobs.
		 *
		 * @since 1.0.0
		 */
		protected function _setup_classes() {
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
		 * Fetches the Logger instance.
		 *
		 * @since 1.0.0
		 *
		 * @return Core\Abstracts\Logger
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
		 * Fires up the plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return \Plugin_Name_Replace_Me\Plugin_Name_Replace_Me
		 */
		public static function init() {
			if ( ! isset( self::$instance ) ) {
				global $wp_version;
				$supports_wp_version  = version_compare( $wp_version, self::$minimum_wp_version, '>=' );
				$supports_php_version = version_compare( phpversion(), self::$minimum_php_version, '>=' );

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

				} else {
					$self           = new self;
					self::$instance = new \WP_Error(
						'minimum_version_not_met',
						__( sprintf(
							"The Plugin Name Replace Me plugin requires at least WordPress %s, and PHP %s.",
							$self->minimum_wp_version(),
							$self->minimum_php_version()
						), 'plugin-name-replace-me' ),
						array( 'current_wp_version' => $wp_version, 'php_version' => phpversion() )
					);

					add_action( 'admin_notices', array( $self, 'below_version_notice' ) );
				}
			}

			return self::$instance;
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
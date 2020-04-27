<?php
/**
 * Service Locator for Plugin.
 * This file is the entry point for all files and functionality.
 * It is a singleton, and is accessed using plugin_name_replace_me()
 *
 * @since 1.0.0
 */

namespace Plugin_Name_Replace_Me;

use Plugin_Name_Replace_Me\Core\Abstracts\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Service Locator.
 * Handles autoloading, and service location. Check out the Bootstrap Class's logger method
 * for an example on how to set up a singleton that is accessed through this bootstrap.
 *
 * @since 1.0.0
 */
final class Service_Locator extends Bootstrap {

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

		// Admin Bar Menus
		new Loaders\Admin_Bar_Menus;

		// Scripts
		$this->scripts();

		// Styles
		$this->styles();

		// REST Endpoints
		// new Loaders\Rest_Endpoints;

		// Custom Post Types
		// new Loaders\Custom_Post_Types;

		// Shortcodes
		// new Loaders\Shortcodes;

		// Widgets
		// new Loaders\Widgets;

		// Admin Pages
		// new Loaders\Admin_Pages;
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

			// First, check to make sure the minimum requirements are met.
			if ( self::plugin_is_supported() ) {

				// Setup the plugin, if requirements were met.
				self::$instance = new self;
				self::$instance->setup();

			} else {

				// Run unsupported actions if requirements are not met.
				$self = new self;
				$self->unsupported_actions();
			}
		}

		return self::$instance;
	}
}
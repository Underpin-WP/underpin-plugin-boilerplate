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
Author URI: https://www.designframesolutions.com
*/

// Bail if someone's trying to be cute, and access this file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The current version of this plugin. Bump this when you release an update.
define( 'PLUGIN_NAME_REPLACE_ME_PLUGIN_VERSION', '1.0.0' );

// The minimum WP version this plugin supports.
define( 'PLUGIN_NAME_REPLACE_ME_MINIMUM_WP_VERSION', '5.0' );

// The minimum PHP version this plugin supports.
define( 'PLUGIN_NAME_REPLACE_ME_MINIMUM_PHP_VERSION', '5.6' );

// The URL for this plugin. Used in asset loading.
define( 'PLUGIN_NAME_REPLACE_ME_URL', plugin_dir_url( __FILE__ ) );

// The CSS URL for this plugin. Used in asset loading.
define( 'PLUGIN_NAME_REPLACE_ME_CSS_URL', PLUGIN_NAME_REPLACE_ME_URL . 'assets/css/build/' );

// The JS URL for this plugin. Used in asset loading.
define( 'PLUGIN_NAME_REPLACE_ME_JS_URL', PLUGIN_NAME_REPLACE_ME_URL . 'assets/js/build/' );

// Root directory for this plugin.
define( 'PLUGIN_NAME_REPLACE_ME_ROOT_DIR', plugin_dir_path( __FILE__ ) );

// Root file for this plugin. Used in activation hooks.
define( 'PLUGIN_NAME_REPLACE_ME_ROOT_FILE', __FILE__ );

// The template directory. Used by the template loader to determine where templates are stored.
define( 'PLUGIN_NAME_REPLACE_ME_TEMPLATE_DIR', PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'templates/' );

// Load in the bootstrap abstraction. This holds most of the default values and keeps your bootstrap file clean.
require_once( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'lib/core/abstracts/Bootstrap.php' );

// Load in the bootstrap that runs the rest of the plugin.
require_once( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'lib/Service_Locator.php' );

/**
 * Fetches the instance of the plugin.
 * This function makes it possible to access everything else in this plugin.
 * It will automatically initiate the plugin, if necessary.
 * It also handles autoloading for any class in the plugin.
 *
 * @since 1.0.0
 *
 * @return Plugin_Name_Replace_Me\Service_Locator
 */
function plugin_name_replace_me() {
	return Plugin_Name_Replace_Me\Service_Locator::init();
}

//Instantiate, and set up the plugin.
plugin_name_replace_me();
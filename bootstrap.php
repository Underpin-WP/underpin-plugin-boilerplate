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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Setup Underpin
require_once( plugin_dir_path( __FILE__ ) . 'lib/underpin/Underpin.php' );

// Load in the bootstrap that runs the rest of the plugin.
require_once( plugin_dir_path( __FILE__ ) . 'lib/Service_Locator.php' );

/**
 * Fetches the instance of the plugin.
 * This function makes it possible to access everything else in this plugin.
 * It will automatically initiate the plugin, if necessary.
 * It also handles autoloading for any class in the plugin.
 *
 * @since 1.0.0
 *
 * @return Service_Locator|Underpin\Abstracts\Underpin The bootstrap for this plugin
 */
function plugin_name_replace_me() {
	return ( new Plugin_Name_Replace_Me\Service_Locator )->get( __FILE__ );
}

//Instantiate, and set up the plugin.
plugin_name_replace_me();
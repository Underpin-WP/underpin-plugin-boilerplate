<?php
/*
Plugin Name: Plugin Name Replace Me
Description: Plugin Description Replace Me
Version: 1.0.0
Author: An awesome developer
Text Domain: plugin_name_replace_me
Domain Path: /languages
Requires at least: 5.1
Requires PHP: 7.0
Author URI: https://www.designframesolutions.com
*/

use Underpin\Abstracts\Underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetches the instance of the plugin.
 * This function makes it possible to access everything else in this plugin.
 * It will automatically initiate the plugin, if necessary.
 * It also handles autoloading for any class in the plugin.
 *
 * @since 1.0.0
 *
 * @return \Underpin\Factories\Underpin_Instance The bootstrap for this plugin.
 */
function plugin_name_replace_me() {
	return Underpin::make_class( [
		'root_namespace'      => 'Plugin_Name_Replace_Me',
		'text_domain'         => 'plugin_name_replace_me',
		'minimum_php_version' => '7.0',
		'minimum_wp_version'  => '5.1',
		'version'             => '1.0.0',
	] )->get( __FILE__ );
}

// Lock and load.
plugin_name_replace_me();

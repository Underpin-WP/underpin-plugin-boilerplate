<?php
/*
Plugin Name: Custom Blocks
Description: Custom blocks for this site
Version: 1.0.0
Author: An awesome developer
Text Domain: blocks
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
function custom_blocks() {
	return Underpin::make_class( [
		'root_namespace'      => 'Blocks',
		'text_domain'         => 'blocks',
		'minimum_php_version' => '7.0',
		'minimum_wp_version'  => '5.1',
		'version'             => '1.0.0',
	] )->get( __FILE__ );
}

// Lock and load.
custom_blocks()->scripts()->add( 'custom_blocks', [
	'handle'      => 'custom_blocks',
	'name'        => 'Editor Blocks',
	'description' => 'Registers blocks in the editor',
	'src'         => custom_blocks()->js_url() . 'index.js',
	'deps'        => custom_blocks()->dir() . 'build/index.asset.php',
	'middlewares' => [
		new \Underpin\Scripts\Factories\Enqueue_Block_Script( 'enqueue_script' ),
	],
] );
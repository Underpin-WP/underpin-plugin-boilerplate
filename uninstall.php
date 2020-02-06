<?php
/**
 * Uninstall actions
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Destroy Error Log
if ( plugin_name_replace_me()->logger() instanceof Plugin_Name_Replace_Me\Utilities\Basic_Logger ) {
	plugin_name_replace_me()->logger()->wipe();
}
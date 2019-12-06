<?php
/**
 * Helper Functions, and public-facing functions for third parties.
 * @author: Alex Standiford
 * @date  : 12/3/19
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Includes a file and passes the specified scope items as local scope.
 *
 * @since 1.0.0
 *
 * @param $file  string The file to include
 * @param $scope array The scope items keyed by their variable name.
 * @return bool True if include was successful, false otherwise.
 */
function plugin_name_replace_me_include_file_with_scope( $file, $scope ) {
	if ( file_exists( $file ) ) {
		extract( $scope );
		include $file;

		return true;
	}

	return false;
}
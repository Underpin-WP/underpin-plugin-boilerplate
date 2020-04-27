<?php
/**
 * $FILE_DESCRIPTION
 *
 * @since   $VERSION
 * @package $PACKAGE
 */

namespace Plugin_Name_Replace_Me\Core\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Feature_Extension {

	/**
	 * Callback to do the actions to register whatever this class is intended to extend.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function do_actions();
}
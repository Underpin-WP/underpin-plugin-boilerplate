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
	 * A human-readable description of this event type.
	 * This is used in debug logs to make it easier to understand why this exists.
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * A human-readable name for this event type.
	 * This is used in debug logs to make it easier to understand what this is.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * Callback to do the actions to register whatever this class is intended to extend.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function do_actions();
}
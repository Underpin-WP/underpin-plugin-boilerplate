<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Plugin_Name_Replace_Me\Loaders;



use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Event_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Logger
 * Houses methods to manage event logging
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Loaders
 */
class Logger extends Event_Registry {

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		$this->add( 'error', 'Plugin_Name_Replace_Me\Core\Events\Error' );
	}
}
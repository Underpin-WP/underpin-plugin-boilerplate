<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Underpin\Loaders;

use Underpin\Abstracts\Registries\Event_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Logger
 * Houses methods to manage event logging
 *
 * @since   1.0.0
 * @package Underpin\Loaders
 */
class Logger extends Event_Registry {

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		$this->add( 'error', 'Underpin\Events\Error' );
	}
}
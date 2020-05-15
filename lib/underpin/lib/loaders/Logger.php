<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Underpin\Loaders;

use Underpin\Abstracts\Registries\Event_Registry;
use Underpin\Factories\Log_Item;
use WP_Error;
use function Underpin\underpin;

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

		if ( true === WP_DEBUG ) {
			$this->add( 'warning', 'Underpin\Events\Warning' );
			$this->add( 'notice', 'Underpin\Events\Notice' );
		}
	}

	/**
	 * Gathers errors from a set of variables.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed ...$items
	 * @return WP_Error
	 */
	public static function gather_errors( ...$items ) {
		$errors = new WP_Error();
		$items  = func_get_args();
		foreach ( $items as $item ) {
			if ( $item instanceof WP_Error ) {
				$errors->add( $item->get_error_code(), $item->get_error_message(), $item->get_error_data() );
			} elseif ( $item instanceof Log_Item ) {
				$errors->add( $item->code, $item->message, $item->data );
			}
		}

		return $errors;
	}
}
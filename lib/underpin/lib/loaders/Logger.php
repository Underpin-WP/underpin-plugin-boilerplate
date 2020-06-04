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
			self::extract( $errors, $item );
		}

		return $errors;
	}

	/**
	 * Appends errors to a WP_Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error          $error    The error to append to. Passed by reference.
	 * @param Log_Item|WP_Error $log_item The log item to append. If this has multiple errors, it will append all of them.
	 * @return void
	 */
	public static function extract( WP_Error &$error, $log_item ) {

		// Transform the log item into a WP_Error, if it is a Log_item
		if ( $log_item instanceof Log_Item ) {
			$log_item = $log_item->error();
		}

		// Append the error, if it is an error.
		if ( $log_item instanceof WP_Error ) {
			foreach ( $log_item->get_error_codes() as $code ) {
				$error->add( $code, $log_item->get_error_message( $code ), $log_item->get_error_data( $code ) );
			}
		}
	}
}
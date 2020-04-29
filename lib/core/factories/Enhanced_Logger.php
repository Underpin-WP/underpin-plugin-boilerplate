<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Plugin_Name_Replace_Me\Core\Factories;


use Plugin_Name_Replace_Me\Core\Abstracts\Writer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Enhanced
 *
 *
 * @since
 * @package
 */
class Enhanced_Logger extends Writer {

	/**
	 * @inheritDoc
	 */
	protected function write( Log_Item $item ) {
		if ( function_exists( 'dfsm' ) ) {
			dfsm()->events()->get_event_log_object( $this->event_type->type )->log_event(
				$item->code,
				$item->message,
				$item->ref,
				$item->data
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function clear() {
		if ( function_exists( 'dfsm' ) ) {
			dfsm()->events()->get_event_log_object( $this->event_type->type )->purge();
		}
	}

	/**
	 * @inheritDoc
	 */
	public function purge( $max_file_age ) {
		if ( function_exists( 'dfsm' ) ) {
			dfsm()->events()->get_event_log_object( $this->event_type->type )->purge();
		}
	}
}
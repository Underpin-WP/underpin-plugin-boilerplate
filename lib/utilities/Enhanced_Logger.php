<?php
/**
 * $
 * @author: Alex Standiford
 * @date  : 12/7/19
 */


namespace Plugin_Name_Replace_Me\Utilities;


use Plugin_Name_Replace_Me\Abstracts\Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Enhanced_Logger extends Logger {

	/**
	 * @inheritDoc
	 */
	public function log( $type, $code, $message, $ref = null, $data = array() ) {
		if ( function_exists( 'dfsm' ) ) {
			dfsm()->events()->get_event_log_object( $type )->log_event(
					$code,
					$message,
					$ref,
					$data
			);

			return new \WP_Error( $code, $message, $data );
		} else {
			return new \WP_Error( 'dfsm_not_active', 'Error log failed - DFS Monitor plugin is not active.' );
		}
	}
}
<?php
/**
 * Enhanced Logging Utility
 * Integrates with DFS Monitoring.
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Utilities
 */


namespace Plugin_Name_Replace_Me\Core\Utilities;


use Plugin_Name_Replace_Me\Core\Abstracts\Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Enhanced_Logger
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Utilities
 */
class Enhanced_Logger extends Logger {

	/**
	 * Logs an error
	 *
	 * @since 1.0.0
	 *
	 * @param string $type    Error log type
	 * @param string $code    Error code
	 * @param string $message Error message
	 * @param mixed  $ref     Reference value, typically a post ID or some database key.
	 * @param array  $data    array Data associated with this error message
	 * @return \WP_Error WP Error, with error message.
	 */
	public function log( $type, $code, $message, $ref = null, $data = array() ) {
		if ( function_exists( 'dfsm' ) ) {
			dfsm()->events()->get_event_log_object( $type )->log_event(
				$code,
				$message,
				$ref,
				$data
			);

		}

		return parent::log( $type, $code, $message, $ref, $data );
	}
}
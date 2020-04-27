<?php
/**
 * Event Logger class
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;

use Exception;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Logger
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */
abstract class Logger {

	/**
	 * Registry of events to log.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $events = [];

	/**
	 * Enqueues an event to be logged in the system
	 *
	 * @since 1.0.0
	 *
	 * @param string $type    Event log type
	 * @param string $code    The event code to use.
	 * @param string $message The message to log.
	 * @param int    $ref     A reference ID related to this error, such as a post ID.
	 * @param array  $data    Arbitrary data associated with this event message.
	 * @return \WP_Error WP Error, with error message.
	 */
	public function log( $type, $code, $message, $ref = null, $data = array() ) {
		$log_message = $code . ' - ' . $message;

		if ( $ref !== null ) {
			$data['ref'] = $ref;
		}

		if ( ! empty( $data ) ) {
			$log_message .= "\n data:" . var_export( (object) $data, true );
		}

		$this->events[ $type ][] = date( 'm/d/Y H:i' ) . ': ' . $log_message;

		return new \WP_Error( $code, $message, $data );
	}

	/**
	 * Retrieves all events that have happened for this request.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $type The event type to retrieve. If false, this will get all events.
	 * @return array|\WP_Error list of all events, or a WP_Error if something went wrong.
	 */
	public function get_request_events( $type = false ) {
		if ( false !== $type ) {
			if ( isset( $this->events[ $type ] ) ) {
				return $this->events[ $type ];
			} else {
				return $this->log(
					'plugin_name_replace_me_error',
					'request_events_invalid_type',
					'The provided event type does not exist.',
					$type,
					[ 'valid_types' => array_keys( $this->events ) ]
				);
			}
		}

		return $this->events;
	}

	/**
	 * Logs an error using a WP Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $type     Error log type
	 * @param WP_Error $wp_error Instance of WP_Error to use for log
	 * @param mixed    $ref      Reference value, typically a post ID or some database key.
	 * @param array    $data     array Data associated with this error message
	 * @return WP_Error WP Error, with error message.
	 */
	public function log_wp_error( $type, WP_Error $wp_error, $ref = null ) {
		return $this->log( $type, $wp_error->get_error_code(), $wp_error->get_error_message(), $ref, $wp_error->get_error_data() );
	}

	/**
	 * Logs an error using a WP Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $type      Error log type
	 * @param Exception $exception Exception instance to log.
	 * @param mixed     $ref       Reference value, typically a post ID or some database key.
	 * @param array     $data      array Data associated with this error message
	 * @return WP_Error WP Error, with error message.
	 */
	public function log_exception( $type, Exception $exception, $ref = null, $data = array() ) {
		return $this->log( $type, $exception->getCode(), $exception->getMessage(), $ref, $data );
	}
}
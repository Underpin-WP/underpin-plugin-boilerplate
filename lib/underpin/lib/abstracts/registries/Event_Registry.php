<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Underpin\Abstracts\Registries;


use Exception;
use Underpin\Abstracts\Event_Type;
use Underpin\Factories\Log_Item;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Logger
 *
 *
 * @since
 * @package
 */
abstract class Event_Registry extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Underpin\Abstracts\Event_Type';

	/**
	 * @inheritDoc
	 */
	public function add( $key, $value ) {
		$valid = parent::add( $key, $value );

		// If valid, set up actions.
		if ( true === $valid ) {
			$this->get( $key )->do_actions();
		}
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
			$events = $this->get( $type );

			// Return the error.
			if ( is_wp_error( $events ) ) {
				return $events;
			} else {
				return (array) $events;
			}

		} else {
			$result = [];
			foreach ( $this as $type => $events ) {
				$result[ $type ] = (array) $this->get( $type );
			}

			return $result;
		}
	}

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
	 * @return Log_Item|WP_Error Log item, with error message. WP_Error if something went wrong.
	 */
	public function log( $type, $code, $message, $ref = null, $data = array() ) {
		$event_type = $this->get( $type );

		if ( is_wp_error( $event_type ) ) {
			return $event_type;
		}

		return $event_type->log( $code, $message, $ref, $data );
	}

	/**
	 * Enqueues an event to be logged in the system, and then returns a WP_Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type    Event log type
	 * @param string $code    The event code to use.
	 * @param string $message The message to log.
	 * @param int    $ref     A reference ID related to this error, such as a post ID.
	 * @param array  $data    Arbitrary data associated with this event message.
	 * @return WP_Error Log item, with error message. WP_Error if something went wrong.
	 */
	public function log_as_error( $type, $code, $message, $ref = null, $data = array() ) {
		$item = $this->log( $type, $code, $message, $ref, $data );

		if ( ! is_wp_error( $item ) ) {
			$item = $item->error();
		}

		return $item;
	}

	/**
	 * Logs an error using a WP Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $type     Error log type
	 * @param WP_Error $wp_error Instance of WP_Error to use for log
	 * @param mixed    $ref      Reference value, typically a post ID or some database key.
	 * @return WP_Error WP Error, with error message.
	 */
	public function log_wp_error( $type, WP_Error $wp_error, $ref = null ) {
		return $this->log( $type, $wp_error->get_error_code(), $wp_error->get_error_message(), $ref, $wp_error->get_error_data() );
	}

	/**
	 * Logs an error from within an exception.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $type      Error log type
	 * @param Exception $exception Exception instance to log.
	 * @param mixed     $ref       Reference value, typically a post ID or some database key.
	 * @param array     $data      array Data associated with this error message
	 * @return \WP_Error WP Error, with error message.
	 */
	public function log_exception( $type, Exception $exception, $ref = null, $data = array() ) {
		return $this->log( $type, $exception->getCode(), $exception->getMessage(), $ref, $data );
	}

	/**
	 * @param string $key
	 * @return Event_Type|\WP_Error Event type, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}

	/**
	 * Purge old logged events.
	 *
	 * @since 1.0.0
	 *
	 * @param int $max_file_age The maximum number of days worth of log data to keep.
	 */
	public function purge( $max_file_age ) {
		foreach ( $this as $key => $class ) {
			$writer = $this->get( $key )->writer();

			if ( ! is_wp_error( $writer ) ) {
				$purged = $writer->purge( $max_file_age );

				if ( is_wp_error( $purged ) ) {
					$this->log_wp_error( 'error', $purged );
				}
			}
		}
	}

}
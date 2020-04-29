<?php
/**
 * Event Type Abstraction
 * Handles events related to logging events of a specified type.
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */

namespace Plugin_Name_Replace_Me\Core\Abstracts;


use Exception;
use Plugin_Name_Replace_Me\Core\Factories\Basic_Logger;
use Plugin_Name_Replace_Me\Core\Factories\Enhanced_Logger;
use Plugin_Name_Replace_Me\Core\Factories\Log_Item;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Event_Type
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */
abstract class Event_Type extends \ArrayIterator {

	/**
	 * Event type
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = '';

	/**
	 * Writes this to the log.
	 * Set this to true to cause this event to get written to the log.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $write_to_log = false;

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
	 * Event_Type constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Placeholder to put actions
	 */
	public function do_actions() {
		add_action( 'shutdown', array( $this, 'log_events' ) );
	}

	/**
	 * Log events to the logger.
	 *
	 * @since 1.0.0
	 */
	public function log_events() {
		$writer = $this->writer();

		if ( ! is_wp_error( $writer ) ) {
			$writer->write_events();
			reset( $this );
		}
	}

	/**
	 * Fetch the logger instance, if this class supports logging.
	 *
	 * @since 1.0.0
	 *
	 * @return Writer|WP_Error The logger instance if this can write to log. WP_Error otherwise.
	 */
	public function writer() {
		if ( true !== $this->write_to_log ) {
			return new \WP_Error(
				'event_type_does_not_write',
				'The specified event type does not write to the logger. To change this, set the write_to_log param to true.',
				[ 'logger' => $this->type, 'write_to_log_value' => $this->write_to_log ]
			);
		}

		if ( function_exists( 'dfsm' ) ) {
			return new Enhanced_Logger( $this );
		} else {
			return new Basic_Logger( $this );
		}
	}


	/**
	 * Enqueues an event to be logged in the system.
	 *
	 * @since 1.0.0
	 *
	 * @param string $code    The event code to use.
	 * @param string $message The message to log.
	 * @param int    $ref     A reference ID related to this error, such as a post ID.
	 * @param array  $data    Arbitrary data associated with this event message.
	 * @return Log_Item The logged item.
	 */
	public function log( $code, $message, $ref = null, $data = array() ) {
		$item = new Log_Item( $this->type, $code, $message, $ref, $data );

		$this[] = $item;

		return $item;
	}

	/**
	 * Logs an error using a WP Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $wp_error Instance of WP_Error to use for log
	 * @param mixed    $ref      Reference value, typically a post ID or some database key.
	 * @return Log_Item The logged item.
	 */
	public function log_wp_error( WP_Error $wp_error, $ref = null ) {
		return $this->log( $wp_error->get_error_code(), $wp_error->get_error_message(), $ref, $wp_error->get_error_data() );
	}

	/**
	 * Logs an error using a WP Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param Exception $exception Exception instance to log.
	 * @param mixed     $ref       Reference value, typically a post ID or some database key.
	 * @param array     $data      array Data associated with this error message
	 * @return Log_Item The logged item.
	 */
	public function log_exception( Exception $exception, $ref = null, $data = array() ) {
		return $this->log( $exception->getCode(), $exception->getMessage(), $ref, $data );
	}
}
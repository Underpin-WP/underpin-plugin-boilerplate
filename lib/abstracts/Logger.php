<?php
/**
 * Event Logger class
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */


namespace Plugin_Name_Replace_Me\Abstracts;


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
	 * Logs an error
	 *
	 * @since 1.0.0
	 *
	 * @param string $type    Error log type
	 * @param string $code    Error code
	 * @param string $message Error message
	 * @param mixed  $ref     Reference value, typically a post ID or some database key.
	 * @param array  $data    array Data associated with this error message
	 * @return WP_Error WP Error, with error message.
	 */
	abstract public function log( $type, $code, $message, $ref = null, $data = array() );

	/**
	 * Logs an error using a WP Error object.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $type     Error log type
	 * @param WP_Error $wp_error Instance of WP_Error to use for log
	 * @param mixed     $ref      Reference value, typically a post ID or some database key.
	 * @param array     $data     array Data associated with this error message
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
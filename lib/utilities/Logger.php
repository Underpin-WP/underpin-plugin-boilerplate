<?php
/**
 * Error and Event Logger Utility.
 *
 * Use this utility to log events and errors that you will want to know about when debugging a problem on a production site.
 * All logged items are stored in the logs directory, under the date they're logged, and the event type specified.
 * Each log file is automatically removed after 30 days, by default.
 * If notifications are enabled, the system will attempt to send a message to the specified support address when an error is logged.
 * This can be disabled using the plugin_name_replace_me/logger/support_notifications_enabled filter.
 *
 * @author: Alex Standiford
 * @date  : 2019-09-17
 */


namespace PLUGIN_NAME_REPLACE_ME\Utilities;


use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Logger {

	private $errors = [ 'error' => [], 'event' => [] ];
	private $files = [ 'error' => 'plugin_name_replace_me-error-log', 'event' => 'plugin_name_replace_me-event-log' ];
	private $notifications_enabled;
	const LOG_DIR = PLUGIN_NAME_REPLACE_ME_ROOT_DIR . 'logs/';
	const LOG_EMAIL = 'support@your-address';

	public function __construct() {
		// If our log is bigger than 5 MB, wipe it.
		foreach ( $this->files as $type => $file ) {
			if ( filesize( $this->get_type_file( $type ) ) > 5000000 ) {
				$this->clear_log( $type );
			}
		}

		$this->notifications_enabled = apply_filters( 'plugin_name_replace_me/logger/support_notifications_enabled', true );

		add_action( 'shutdown', [ $this, 'log_items' ] );
		add_action( 'init', array( $this, 'get_log' ) );
	}

	/**
	 * Enqueues an error to be logged in the system
	 *
	 * @since 1.0.0
	 *
	 * @param string $type    Error log type
	 * @param        $message WP_error|string The error to log
	 * @param null   $data    array|null Data associated with this error message
	 * @return array resulting message and data from the log.
	 */
	private function log( $type, $message, $data = null ) {
		if ( is_wp_error( $message ) ) {
			$message     = $message->get_error_message();
			$log_message = $message;
		} else {
			$log_message = $message;
		}
		if ( isset( $data ) ) {
			$log_message .= "\n data:" . json_encode( (object) $data );
		}
		$this->errors[ $type ][] = date( 'm/d/Y H:i' ) . ': ' . $log_message;

		return [ 'message' => $message, 'data' => $data ];
	}

	/**
	 * Logs an error to the error log
	 *
	 * @since 1.0.0
	 *
	 * @param string           $code          The error code to use in the WP_Error object
	 * @param WP_error|string $error_message The error to log
	 * @param mixed            $data          Data associated with this error message
	 * @return WP_Error WP Error, with error message.
	 */
	public function error( $code, $error_message, $data = null ) {
		$log = $this->log( 'error', $error_message, $data );

		return new WP_Error( $code, $log['message'], $log['data'] );
	}

	/**
	 * Logs an error to the error log
	 *
	 * @since 1.0.0
	 *
	 * @param      $event_message WP_error|string The error to log
	 * @param null $data          array|null Data associated with this error message
	 * @return array Event message and data.
	 */
	public function event( $event_message, $data = null ) {
		return $this->log( 'event', $event_message, $data );
	}

	/**
	 * Gets the file name for the specified type.
	 *
	 * @since 1.0.0
	 *
	 * @param $type
	 * @return string
	 */
	private function get_type_file( $type ) {
		$file = PLUGIN_NAME_REPLACE_ME_LOG_DIR . date( 'M-d-y' ) . '_' . $this->files[ $type ] . '.log';
		if ( ! file_exists( $file ) ) {
			fopen( $file, "w" );
		}

		return $file;
	}

	/**
	 * Logs all items to the error log file.
	 *
	 * @since 1.0.0
	 */
	public function log_items() {
		foreach ( $this->errors as $error_type => $errors ) {
			if ( ! empty( $errors ) ) {
				$error_file = implode( "\n\n", $errors );
				file_put_contents( $this->get_type_file( $error_type ), "\n\n" . $error_file, FILE_APPEND );

				if ( 'error' === $error_type ) {
					$this->notify( $error_file );
				}
			}
		}
	}

	/**
	 * Sends a message to support email address.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message The message body
	 * @param string $subject The subject
	 */
	public function notify( $message, $subject = '' ) {
		if ( true === $this->notifications_enabled ) {
			$site_url = site_url();

			if ( ! is_string( $subject ) || empty( $subject ) ) {
				$subject = "Errors on $site_url";
			}

			wp_mail( self::LOG_EMAIL, $subject, $message );
		}
	}

	/**
	 * Clears the error log
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Log type
	 */
	public function clear_log( $type ) {
		file_put_contents( $this->get_type_file( $type ), '' );
	}

	/**
	 * Purges logs older than the specified date. Intended to run on a cron.
	 *
	 * @since 1.0.0
	 *
	 * @param int $max_file_age The maximum number of days worth of log data to keep.
	 */
	public function purge_old_logs( $max_file_age ) {
		$files       = scandir( self::LOG_DIR );
		$oldest_date = date( 'U', strtotime( '-' . $max_file_age . ' days' ) );
		foreach ( $files as $file ) {
			if ( false !== strpos( $file, 'log' ) ) {
				$file_split = explode( '_', $file );
				$file_date  = date( 'U', strtotime( $file_split[0] ) );

				if ( $file_date < $oldest_date ) {
					unlink( self::LOG_DIR . $file );
				}
			}
		}
	}
}
<?php
/**
 * Basic_Logger Utility
 * Logs plugin events to a filesystem.
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Utilities
 */


namespace Plugin_Name_Replace_Me\Utilities;


use Plugin_Name_Replace_Me\Abstracts\Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Basic_Logger
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Utilities
 */
class Basic_Logger extends Logger {

	/**
	 * Registry of events to log.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $events = [];

	/**
	 * Registry of event types, correlated by the file they log to.
	 * Do not use double-underscores (__) in event files. This is a special character used to parse files.
	 *
	 * @since 1.0.0
	 * @var array event file keyed keyed by the event type.
	 */
	private $files = [ 'plugin_name_replace_me_error' => 'plugin-name-replace-me-error-log', 'plugin_name_replace_me_api_event' => 'plugin-name-replace-me-api-event-log' ];

	/**
	 * Logging directory.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $log_dir;

	/**
	 * Basic_Logger constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Construct the log dir
		$upload_dir    = wp_upload_dir( null, false );
		$this->log_dir = trailingslashit( trailingslashit( $upload_dir['basedir'] ) . 'plugin-name-replace-me-event-logs/' );

		// If the log directory does not exist, create it and set permissions.
		if ( ! is_writeable( $this->log_dir ) ) {
			@mkdir( $this->log_dir );
			@chmod( $this->log_dir, 0664 );
		}

		// Construct the events array.
		$this->reset_events();

		add_action( 'shutdown', array( $this, 'log_events' ) );
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
	 * Gets the file name for the specified event type.
	 * This will automatically create the file if it does not exist.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Event log type.
	 * @param string $date Optional. The log file date to retrieve. Default today.
	 * @return string|\WP_Error
	 */
	public function file( $type, $date = 'today' ) {

		if ( ! isset( $this->files[ $type ] ) ) {
			return new \WP_Error( 'event_file_type_does_not_exist', 'The specified event type does not exist.' );
		}

		$file = $this->path( $type, $date );

		if ( ! is_wp_error( $file ) && ! @file_exists( $file ) ) {
			@fopen( $file, "w" );
		}

		return $file;
	}

	/**
	 * Retrieves the path for the specified log type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Event log type.
	 * @param string $date Optional. The log file date to retrieve. Default today.
	 * @return string Path to the specified event log.
	 */
	public function path( $type, $date = 'today' ) {
		if ( ! isset( $this->files[ $type ] ) ) {
			return new \WP_Error( 'event_file_type_does_not_exist', 'The specified event type does not exist.' );
		}

		$date = strtolower( date( 'M-d-y', strtotime( $date ) ) );

		return $this->log_dir . $this->files[ $type ] . '__' . $date . '.log';
	}

	/**
	 * Get all events in the current runtime.
	 *
	 * @since 1.0.0
	 */
	public function events() {
		return $this->events;
	}

	/**
	 * Resets events to the default state.
	 *
	 * @since 1.0.0
	 */
	public function reset_events() {
		$events = [];
		foreach ( $this->files as $event_type => $file ) {
			$events[ $event_type ] = [];
		}

		$this->events = $events;
	}

	/**
	 * Logs all items to the event log file.
	 *
	 * @since 1.0.0
	 */
	public function log_events() {
		foreach ( $this->events as $error_type => $errors ) {
			if ( ! empty( $errors ) ) {
				$file = $this->file( $error_type );
				if ( ! is_wp_error( $file ) ) {
					$error_file = implode( "\n\n", $errors );
					file_put_contents( $file, "\n\n" . $error_file, FILE_APPEND );
				}
			}
		}
	}

	/**
	 * Clears the event log of the specified type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Log type
	 */
	public function clear( $type ) {
		$file = $this->file( $type );

		if ( ! is_wp_error( $file ) ) {
			unlink( $file );
		}
	}

	/**
	 * Gathers a list of log files.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of paths to log files.
	 */
	public function files() {
		$files = glob( $this->log_dir . '*.log' );

		if ( false === $files ) {
			$files = array();
		}

		return $files;
	}

	/**
	 * Removes all event log files.
	 *
	 * @since 1.0.0
	 */
	public function wipe() {
		array_map( 'unlink', $this->files() );
	}

	/**
	 * Attempt to retrieve log type and date from the provided file name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path The path to the file, or the file name.
	 * @return array|\WP_Error Parsed file, or \WP_Error object.
	 */
	public function parse_file( $path ) {
		$types      = array_flip( $this->files );
		$file       = basename( $path );
		$file_split = explode( '__', $file );
		$errors     = new \WP_Error();

		if ( count( $file_split ) !== 2 ) {
			$errors->add(
				'parse_file_malformed_file',
				'The file provided is malformed. The file must contain exactly one double __ between the type and date.',
				compact( 'file', 'file_split' )
			);
		}

		if ( ! isset( $types[ $file_split[0] ] ) ) {
			$errors->add(
				'parse_file_type_does_not_exist',
				'The provided file type does not exist',
				compact( 'file', 'file_split' )
			);
		}

		if ( false === strpos( $file, '.log' ) ) {
			$errors->add(
				'parse_file_type_is_not_log',
				'The provided file is not an error log file',
				compact( 'file' )
			);
		}

		// Backcompat for <5.0
		$has_errors = method_exists( $errors, 'has_errors' ) ? $errors->has_errors() : ! empty( $errors->errors );

		// Bail early if we have any errors.
		if ( true === $has_errors ) {
			return $errors;
		}

		// Remove file extension from date
		$raw_date = str_replace( '.log', '', $file_split[1] );

		// Lookup event type from types list.
		$type = $types[ $file_split[0] ];

		// Set date
		$date = date( 'M-d-Y', strtotime( $raw_date ) );

		$path = $this->path( $type, $date );

		return compact( 'type', 'date', 'path' );
	}

	/**
	 * Purges logs older than the specified date. Intended to run on a cron.
	 *
	 * @since 1.0.0
	 *
	 * @param int $max_file_age The maximum number of days worth of log data to keep.
	 * @return array|\WP_Error List of purged files, or WP_Error.
	 */
	public function purge( $max_file_age ) {
		$files = $this->files();

		// bail early if the max file age is less than zero.
		if ( $max_file_age < 0 ) {
			return new \WP_Error(
				'invalid_max_age',
				'The provided max file age is less than zero. File age must be greater than zero.'
			);
		}

		$purged      = [];
		$oldest_date = date( 'U', strtotime( '-' . $max_file_age . ' days midnight' ) );

		foreach ( $files as $file ) {
			$file_info = $this->parse_file( $file );
			$file_date = date( 'U', strtotime( $file_info['date'] . ' midnight' ) );

			if ( ! is_wp_error( $file_info ) && $file_date < $oldest_date ) {
				$deleted = @unlink( $file_info['path'] );

				if ( true === $deleted ) {
					$purged[] = $file_info['path'];
				}
			}
		}

		return $purged;
	}
}
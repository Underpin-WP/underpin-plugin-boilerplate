<?php
/**
 * BasicLogger Utility
 *
 * @author: Alex Standiford
 * @date  : 2019-09-17
 */


namespace Plugin_Name_Replace_Me\Utilities;


use Plugin_Name_Replace_Me\Abstracts\Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Basic_Logger extends Logger {

	private $errors = array( 'plugin_name_replace_me_error' => array() );
	private $files = array( 'plugin_name_replace_me_error' => 'plugin-name-replace-me-error-log' );
	private $log_dir;
	private $notifications_enabled;

	public function __construct() {
		$this->log_dir = trailingslashit( PLUGIN_NAME_REPLACE_ME_ROOT_DIR . '/event-log' );
		// If our log is bigger than 5 MB, wipe it.
		foreach ( $this->files as $type => $file ) {
			if ( filesize( $this->get_type_file( $type ) ) > 5000000 ) {
				$this->clear_log( $type );
			}
		}

		$this->notifications_enabled = apply_filters( 'plugin_name_replace_me/logger/support_notifications_enabled', true );

		add_action( 'shutdown', array( $this, 'log_items' ) );
	}

	/**
	 * Enqueues an error to be logged in the system
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Error log type
	 * @param        $code
	 * @param        $message
	 * @param null   $ref
	 * @param array  $data array|null Data associated with this error message
	 * @return \WP_Error WP Error, with error message.
	 */
	public function log( $type, $code, $message, $ref = null, $data = array() ) {
		$log_message = $code . ' - ' . $message;

		if ( $ref !== null ) {
			$data[ 'ref' ] = $ref;
		}

		if ( isset( $data ) ) {
			$log_message .= "\n data:" . json_encode( (object) $data );
		}

		$this->errors[ $type ][] = date( 'm/d/Y H:i' ) . ': ' . $log_message;

		return new \WP_Error( $code, $message, $data );
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
		$file = $this->log_dir . date( 'M-d-y' ) . '_' . $this->files[ $type ] . '.log';
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
	 * Sends a message to our support email address.
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

			wp_mail( get_option( 'admin_email' ), $subject, $message );
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
		$files       = scandir( $this->log_dir );
		$oldest_date = date( 'U', strtotime( '-' . $max_file_age . ' days' ) );
		foreach ( $files as $file ) {
			if ( false !== strpos( $file, 'log' ) ) {
				$file_split = explode( '_', $file );
				$file_date  = date( 'U', strtotime( $file_split[ 0 ] ) );

				if ( $file_date < $oldest_date ) {
					unlink( $this->log_dir . $file );
				}
			}
		}
	}
}
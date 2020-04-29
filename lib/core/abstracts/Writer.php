<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;

use Plugin_Name_Replace_Me\Core\Factories\Log_Item;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Writer.
 * This is a factory that is instantiated by error loggers.
 * It handles the steps that actually write of error log events
 *
 * @since 1.0.0
 * @package
 */
abstract class Writer {

	/**
	 * @var Event_Type
	 */
	protected $event_type;

	public function __construct( Event_Type $event_type ) {
		$this->event_type = $event_type;
	}

	/**
	 * Writes a single log item.
	 *
	 * @since 1.0.0
	 *
	 * @param $item
	 * @return mixed
	 */
	abstract protected function write( Log_Item $item );

	/**
	 * Clears the event log.
	 *
	 * @since 1.0.0
	 */
	abstract public function clear();

	/**
	 * Purges logs older than the specified date. Intended to run on a cron.
	 *
	 * @since 1.0.0
	 *
	 * @param int $max_file_age The maximum number of days worth of log data to keep.
	 * @return array|\WP_Error List of purged files, or WP_Error.
	 */
	abstract public function purge( $max_file_age );

	/**
	 * Writes events.
	 *
	 * @since 1.0.0
	 */
	public function write_events() {
		foreach ( $this->event_type as $event ) {
			$this->write( $event );
		}
	}
}
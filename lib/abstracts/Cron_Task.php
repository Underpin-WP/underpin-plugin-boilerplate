<?php
/**
 * Cron Task Abstraction
 * @author: Alex Standiford
 * @date  : 2019-07-29
 */

namespace PLUGIN_NAME_REPLACE_ME\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Cron_Task {

	private $frequency = 'hourly';
	private $event;
	private static $registered_events = [];

	public function __construct( $event, $frequency = 'hourly' ) {
		if ( ! isset( self::$registered_events[ $this->event ] ) ) {
			$this->event     = 'plugin_name_replace_me\sessions\\'.$event;
			$this->frequency = $frequency;

			register_activation_hook( PLUGIN_NAME_REPLACE_ME_ROOT_FILE, [ $this, 'activate' ] );
			add_action( $this->event, [ $this, 'cron_action' ] );
			self::$registered_events[ $this->event ] = $this->frequency;
		}
	}

	/**
	 * Activates the cron task on plugin activation
	 *
	 * @since 1.0.0
	 *
	 */
	public function activate() {
		if ( ! wp_next_scheduled( $this->event ) ) {
			wp_schedule_event( time(), $this->frequency, $this->event );
		}
	}

	/**
	 * The cron action that will fire on the scheduled time.
	 *
	 * @since 1.0.0
	 */
	abstract function cron_action();
}
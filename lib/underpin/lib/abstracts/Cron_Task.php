<?php
/**
 * Cron Task Abstraction
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */

namespace Underpin\Abstracts;

use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cron_Task
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */
abstract class Cron_Task extends Feature_Extension {

	/**
	 * How often the cron task should recur. See wp_get_schedules() for accepted values.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $frequency = 'hourly';

	/**
	 * The name of this event.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $event;

	/**
	 * List of registered events.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private static $registered_events = [];

	/**
	 * The cron action that will fire on the scheduled time.
	 *
	 * @since 1.0.0
	 */
	abstract function cron_action();

	/**
	 * Cron_Task constructor.
	 *
	 * @param string $event     The name of this event.
	 * @param string $frequency How often the cron task should recur. See wp_get_schedules() for accepted values.
	 * @param string $file      The file to use to register the activation hook.
	 */
	public function __construct( $event, $file, $frequency = 'hourly' ) {
		if ( ! isset( self::$registered_events[ $this->event ] ) ) {
			$this->event     = 'underpin\sessions\\' . $event;
			$this->frequency = $frequency;
			$this->file      = $file;

				// Adds the job to the registry.
			self::$registered_events[ $this->event ] = $this->frequency;
		} else {
			underpin()->logger()->log(
				'error',
				'cron_event_exists',
				__( 'A cron event was not registered because an event of the same name has already been registered.' ),
				'',
				array( 'event' => $event, 'frequency' => $frequency )
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		// Registers this cron job to activate when the plugin is activated.
		register_activation_hook( $this->file, [ $this, 'activate' ] );

		// Registers the action that fires when the cron job runs
		add_action( $this->event, [ $this, 'cron_action' ] );
	}

	/**
	 * Activates the cron task on plugin activation
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		// If this event is not scheduled, schedule it.
		if ( ! wp_next_scheduled( $this->event ) ) {
			wp_schedule_event( time(), $this->frequency, $this->event );
		}
	}
}
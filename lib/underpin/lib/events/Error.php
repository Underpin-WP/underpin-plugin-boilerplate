<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Underpin\Events;


use Underpin\Abstracts\Event_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Error
 * Error event type.
 *
 * @since 1.0.0
 *
 * @since
 * @package
 */
class Error extends Event_Type {

	/**
	 * Event type
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'error';

	/**
	 * Writes this to the log.
	 * Set this to true to cause this event to get written to the log.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $write_to_log = true;

	/**
	 * @var inheritDoc
	 */
	public $description = 'Intended to log events when something goes wrong while running this plugin.';

	/**
	 * @var inheritDoc
	 */
	public $name = "Error";

	/**
	 * @inheritDoc
	 */
	protected $purge_frequency = 7;
}
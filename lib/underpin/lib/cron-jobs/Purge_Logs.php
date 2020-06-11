<?php
/**
 * Cron task to purge error logs.
 *
 * @since   1.0.0
 * @package Underpin\Cron
 */


namespace Underpin\Cron_Jobs;

use function Underpin\Underpin;

use Underpin\Abstracts\Cron_Task;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Purge_Logs
 *
 * @since   1.0.0
 * @package Underpin\Cron
 */
class Purge_Logs extends Cron_Task {

	public $description = 'Purges logged events that are older than 30 days. This keeps the log files from becoming massive.';

	public $name = 'Purge Logs';

	/**
	 * Purge_Logs constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct( 'underpin_logs', 'daily' );
	}

	/**
	 * @inheritDoc
	 */
	function cron_action() {

		/**
		 * Filters the max file age for items purged via the cron.
		 *
		 * @since 1.0.0
		 */
		$max_age = apply_filters( 'underpin/cron/purge_logs/max_age', 30 );

		underpin()->logger()->purge( $max_age );
	}
}
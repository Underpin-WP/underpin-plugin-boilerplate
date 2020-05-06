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
		parent::__construct( 'plugin_name_replace_me_purge_logs', underpin()->root_file(), 'daily' );
	}

	/**
	 * @inheritDoc
	 */
	function cron_action() {
		underpin()->logger()->purge( 30 );
	}
}
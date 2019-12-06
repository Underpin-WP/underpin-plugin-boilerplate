<?php
/**
 * Cron task to update the sync queue$
 *
 * @author: Alex Standiford
 * @date  : 12/4/19
 */


namespace PLUGIN_NAME_REPLACE_ME\Cron;


use PLUGIN_NAME_REPLACE_ME\Abstracts\Cron_Task;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Purge_Logs extends Cron_Task {

	public function __construct() {
		parent::__construct( 'plugin_name_replace_me_purge_logs', 'daily' );
	}

	/**
	 * @inheritDoc
	 */
	function cron_action() {
		plugin_name_replace_me()->logger()->purge_old_logs( 30 );

	}
}
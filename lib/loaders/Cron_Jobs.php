<?php
/**
 * Cron Jobs
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Cron_Task;
use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cron_Jobs
 * Registry for Cron Jobs
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */

class Cron_Jobs extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Plugin_Name_Replace_Me\Core\Abstracts\Cron_Task';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		$this->add( 'purge_logs', 'Plugin_Name_Replace_Me\Core\Cron_Jobs\Purge_Logs' );
	}

	/**
	 * @param string $key
	 * @return Cron_Task|\WP_Error Script Resulting cron task class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
<?php
/**
 * Batch_Tasks
 *
 * @since   1.0.0
 * @package DFS_Monitor\Registries\Loaders
 */


namespace Underpin\Loaders;

use Underpin\Abstracts\Registries\Loader_Registry;
use Underpin\Abstracts\Batch_Task;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Batch_Tasks
 * Registry for Cron Jobs
 *
 * @since   1.0.0
 * @package DFS_Monitor\Registries\Loaders
 */
class Batch_Tasks extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Underpin\Abstracts\Batch_Task';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		// $this->add();
	}

	/**
	 * @param string $key
	 * @return Batch_Task|\WP_Error Script Resulting block class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
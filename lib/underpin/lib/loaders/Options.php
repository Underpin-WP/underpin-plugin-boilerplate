<?php
/**
 * Options
 *
 * @since   1.0.0
 * @package DFS_Monitor\Registries\Loaders
 */


namespace Underpin\Loaders;

use Underpin\Abstracts\Registries\Loader_Registry;
use Underpin\Factories\Option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Options
 * Registry for Cron Jobs
 *
 * @since   1.0.0
 * @package DFS_Monitor\Registries\Loaders
 */
class Options extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Underpin\Factories\Option';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
	}

	/**
	 * @param string $key
	 * @return Option|\WP_Error Script Resulting block class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
<?php
/**
 * Options
 *
 * @since   1.0.0
 * @package DFS_Monitor\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Core\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;
use Plugin_Name_Replace_Me\Core\Factories\Option;

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
	protected $abstraction_class = 'Plugin_Name_Replace_Me\Core\Factories\Option';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		$this->add( 'plugin_name_replace_me_version',
			new Option(
				'plugin_name_replace_me_version',
				'The version of this plugin. Used in upgrade routines',
				'Plugin Version',
				PLUGIN_NAME_REPLACE_ME_PLUGIN_VERSION )
		);
	}

	/**
	 * @param string $key
	 * @return Option|\WP_Error Script Resulting block class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
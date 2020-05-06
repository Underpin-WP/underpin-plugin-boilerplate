<?php
/**
 * Blocks
 *
 * @since   1.0.0
 * @package Underpin\Registries\Loaders
 */


namespace Underpin\Loaders;

use Underpin\Abstracts\Registries\Loader_Registry;
use Underpin\Abstracts\Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Blocks
 * Registry for Cron Jobs
 *
 * @since   1.0.0
 * @package Underpin\Registries\Loaders
 */

class Blocks extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Underpin\Abstracts\Block';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		// $this->add( 'key','namespace\to\block\class' );
	}

	/**
	 * @param string $key
	 * @return Block|\WP_Error Script Resulting block class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}
}
<?php
/**
 * Shortcodes
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Shortcodes
 * Registry for Cron Jobs
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */

class Shortcodes extends Loader_Registry {

	public function __construct() {
		parent::__construct( 'Plugin_Name_Replace_Me\Abstracts\Shortcode' );
	}

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
		//$this->add( 'key','namespaced_class' );
	}

}
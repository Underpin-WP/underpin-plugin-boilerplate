<?php
/**
 * Taxonomy Registry
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Custom_Post_Type;
use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;
use Plugin_Name_Replace_Me\Core\Abstracts\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Custom_Post_Types
 * Registry for Taxonomies
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */
class Taxonomies extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = 'Plugin_Name_Replace_Me\Core\Abstracts\Taxonomy';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
//		$this->add( 'taxonomy', 'Namespace\To\Class');
	}

	/**
	 * @param string $key
	 * @return Taxonomy|\WP_Error Script Resulting REST Endpoint class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}

}
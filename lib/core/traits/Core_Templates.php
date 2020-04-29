<?php
/**
 * Template Loader Trait
 * Handles template loading and template inheritance.
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Traits
 */

namespace Plugin_Name_Replace_Me\Core\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait Templates
 *
 * @since   1.0.0
 * @package plugin_name_replace_me\traits
 */
trait Core_Templates {
	use Templates;


	/**
	 * Gets the template directory based on the template group.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_template_directory() {
		$template_group     = $this->get_template_group();
		$template_directory = trailingslashit( PLUGIN_NAME_REPLACE_ME_ROOT_DIR ) . 'lib/core/templates/' . $template_group;

		return $template_directory;
	}

}
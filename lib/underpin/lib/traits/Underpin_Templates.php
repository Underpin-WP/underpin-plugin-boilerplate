<?php
/**
 * Template Loader Trait
 * Handles template loading and template inheritance.
 *
 * @since   1.0.0
 * @package Underpin\Traits
 */

namespace Underpin\Traits;

use Underpin\Abstracts\Underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core Template Trait.
 * Creates templates based off of the location of Underpin.
 *
 * @since   1.0.0
 * @package plugin_name_replace_me\traits
 */
trait Underpin_Templates {
	use Templates;

	protected function get_template_root_path() {
		return Underpin::dir();
	}

}
<?php
/**
 * Style Loader
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */


namespace Plugin_Name_Replace_Me\Loaders;

use Plugin_Name_Replace_Me\Core\Abstracts\Registries\Loader_Registry;
use Plugin_Name_Replace_Me\Core\Abstracts\Style;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Styles
 * Loader for styles
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Registries\Loaders
 */
class Styles extends Loader_Registry {

	/**
	 * @inheritDoc
	 */
	protected $abstraction_class = '\Plugin_Name_Replace_Me\Core\Abstracts\Style';

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
//		$this->add( 'debug', 'namespace\to\style\class' );
	}

	/**
	 * @param string $key
	 * @return Style|\WP_Error Script Resulting script class, if it exists. WP_Error, otherwise.
	 */
	public function get( $key ) {
		return parent::get( $key );
	}

	/**
	 * Enqueues a script.
	 * This is essentially a wrapper for wp_localize_script and wp_enqueue_script.
	 * The script is only localized if the class specifies localized values to pass.
	 * The script uses the value of $handle to set the variable in Javascript.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle The script that should be enqueued.
	 * @return true|\WP_Error True if the style was enqueued, a WP Error otherwise.
	 */
	public function enqueue( $handle ) {
		$style = $this->get( $handle );
		if ( $style instanceof Style ) {
			$style->enqueue();

			return true;
		} else {
			return plugin_name_replace_me()->logger()->log_as_error(
				'error',
				'style_not_enqueued',
				'The specified style could not be enqueued because it has not been registered.',
				$handle
			);
		}
	}
}
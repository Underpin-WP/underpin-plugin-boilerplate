<?php
/**
 * Settings Select Field
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Factories\Settings_Fields
 */


namespace Plugin_Name_Replace_Me\Core\Factories\Settings_Fields;

use Plugin_Name_Replace_Me\Core\Abstracts\Settings_Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Select
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Factories\Settings_Fields
 */
class Select extends Settings_Field {

	/**
	 * @inheritDoc
	 */
	function get_field_type() {
		return 'select';
	}

	/**
	 * @inheritDoc
	 */
	function sanitize( $value ) {
		return (string) $value;
	}
}
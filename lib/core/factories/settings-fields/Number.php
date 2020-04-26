<?php
/**
 * Settings Text Field
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Factories\Settings_Fields
 */


namespace Plugin_Name_Replace_Me\Factories\Settings_Fields;


use Plugin_Name_Replace_Me\Abstracts\Settings_Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Number
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Factories\Settings_Fields
 */
class Number extends Settings_Field {

	/**
	 * @inheritDoc
	 */
	function get_field_type() {
		return 'number';
	}

	/**
	 * @inheritDoc
	 */
	function sanitize( $value ) {
		return (float) $value;
	}
}
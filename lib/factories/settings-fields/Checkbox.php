<?php
/**
 * Settings Checkbox Field.
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
 * Class Checkbox
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Factories\Settings_Fields
 */
class Checkbox extends Settings_Field {

	/**
	 * @inheritDoc
	 */
	function get_field_type() {
		return 'checkbox';
	}

	/**
	 * @inheritDoc
	 */
	function sanitize( $value ) {
		return (boolean) $value;
	}

}
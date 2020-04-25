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
 * Class Text
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Factories\Settings_Fields
 */
class Text extends Settings_Field {

	/**
	 * @inheritDoc
	 */
	function get_field_type() {
		return 'text';
	}

	/**
	 * @inheritDoc
	 */
	function sanitize( $value ) {
		return (string) $value;
	}
}
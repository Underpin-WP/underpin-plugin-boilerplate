<?php
/**
 * Button Field
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Factories\Settings_Fields
 */


namespace Plugin_Name_Replace_Me\Core\Factories\Settings_Fields;

use Plugin_Name_Replace_Me\Core\Abstracts\Settings_Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Number
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Factories\Settings_Fields
 */
class Button extends Settings_Field {

	/**
	 * Settings_Field constructor.
	 *
	 * @param mixed $value  The current value of the field.
	 * @param array $params The field parameters
	 */
	public function __construct( $value, array $params = [] ) {
		parent::__construct( $value, $params );
	}

	/**
	 * @inheritDoc
	 */
	function get_field_type() {
		return 'button';
	}

	/**
	 * @inheritDoc
	 */
	function sanitize( $value ) {
		return true;
	}
}
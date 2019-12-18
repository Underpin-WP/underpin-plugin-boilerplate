<?php
/**
 * Registers a shortcode
 *
 * @author: Alex Standiford
 * @date  : 12/6/19
 */


namespace Plugin_Name_Replace_Me\Abstracts;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Shortcode {

	public function __construct( $shortcode, array $defaults ) {
		$this->defaults = $defaults;
		add_shortcode( $shortcode, [ $this, 'shortcode' ] );
	}

	/**
	 * The actual shortcode callback. Sets shortcode atts to the class so other methods can access the arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string $atts The shortcode attributes
	 * @return mixed The shortcode action result.
	 */
	public function shortcode( $atts ) {
		$this->atts = shortcode_atts( $this->defaults, $atts );

		return $this->shortcode_actions();
	}

	/**
	 * The actions this shortcode should take when called. use $this->atts to access the parsed shortcode atts.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed The shortcode action result.
	 */
	public abstract function shortcode_actions();

}
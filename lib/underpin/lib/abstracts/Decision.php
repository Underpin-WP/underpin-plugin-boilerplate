<?php
/**
 * A single decision for a decision list
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin\Abstracts;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Decision
 * Class decision
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */
abstract class Decision {

	public $id = '';
	public $name = '';
	public $description = '';
	public $priority = 0;

	abstract public function is_valid( $params = [] );

	abstract public function valid_actions( $params = [] );

}
<?php
/**
 * WordPress Block Abstraction
 *
 * @since   1.0.0
 * @package Lib\Core\Abstracts
 */


namespace Underpin\Abstracts;

use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Block
 * WordPress Block Class
 *
 * @since   1.0.0
 * @package Lib\Core\Abstracts
 */
abstract class Block extends Feature_Extension {

	/**
	 * The registered block.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public $type = false;

	/**
	 * Args to pass when registering the block.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $args = [];

	/**
	 * The script that should be registered alongside this block, if any.
	 * This expects to be the name of a Script class that can be instantiated.
	 *
	 * @since 1.0.0
	 *
	 * @var string|bool|Script Class name, or declaration. False if no script is used.
	 */
	public $script = false;

	/**
	 * The style that should be registered alongside this block, if any.
	 * This expects to be the name of a Style class that can be instantiated.
	 *
	 * @since 1.0.0
	 *
	 * @var string|bool|Style Class name, or declaration. False if no style is used.
	 */
	public $style = false;

	/**
	 * Block constructor.
	 */
	public function __construct() {

		if ( false === $this->type ) {
			underpin()->logger()->log(
				'error',
				'invalid_block_type',
				'The provided block does not appear to have a type set',
				get_class( $this ),
				[ 'type' => $this->type, 'expects' => 'string' ]
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Registers the block type.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$registered = register_block_type( $this->type, $this->args );
		if ( false === $registered ) {
			underpin()->logger()->log(
				'error',
				'block_not_registered',
				'The provided block failed to register. Register block type provides a __doing_it_wrong warning explaining more.',
				$this->type,
				[ 'type' => $this->type, 'expects' => 'string' ]
			);
		} else {
			underpin()->logger()->log(
				'notice',
				'block_registered',
				'The provided block was registered successfully.',
				$this->type,
				[ 'args' => $this->args ]
			);
		}
	}

}
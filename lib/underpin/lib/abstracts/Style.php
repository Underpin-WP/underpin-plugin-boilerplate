<?php
/**
 * Registers Scripts to WordPress
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin\Abstracts;

use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Script
 * Class Scripts
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */
abstract class Style extends Feature_Extension {

	/**
	 * The handle for this script.
	 *
	 * @since 1.0.0
	 * @var string the script handle.
	 */
	protected $handle;

	/**
	 * The version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $ver = false;

	/**
	 * The source url for this script.
	 *
	 * @since 1.0.0
	 * @var bool|string
	 */
	protected $src = false;

	/**
	 * The dependencies for this script.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $deps = [];

	/**
	 * The media for which this stylesheet has been defined.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $media = 'all';

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		add_action( 'init', [ $this, 'register' ] );
	}

	public function register() {
		$registered = wp_register_style( $this->handle, $this->src, $this->deps, $this->ver, $this->media );

		if ( false === $registered ) {
			underpin()->logger()->log(
				'error',
				'style_was_not_registered',
				'The style ' . $this->handle . ' failed to register. That is all I know, unfortunately.',
				$this->handle
			);
		} else {
			underpin()->logger()->log(
				'notice',
				'style_was_registered',
				'The style ' . $this->handle . ' registered successfully.',
				$this->handle
			);
		}
	}

	public function enqueue() {
		wp_enqueue_style( $this->handle );

		underpin()->logger()->log(
			'notice',
			'style_was_enqueued',
			'The style ' . $this->handle . ' has been enqueued.',
			$this->handle
		);
	}

}
<?php
/**
 * Registers Scripts to WordPress
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin\Abstracts;

use Underpin\Traits\Feature_Extension;
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
abstract class Style {
	use Feature_Extension;
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

		// Confirm it was enqueued.
		if ( wp_style_is( $this->handle, 'enqueued' ) ) {

			underpin()->logger()->log(
				'notice',
				'style_was_enqueued',
				'The style ' . $this->handle . ' has been enqueued.',
				$this->handle
			);

		} else {
			underpin()->logger()->log(
				'error',
				'style_failed_to_enqueue',
				'The style ' . $this->handle . ' failed to enqueue.',
				$this->handle
			);
		}

	}

	public function __get( $key ) {
		if ( isset( $this->$key ) ) {
			return $this->$key;
		} else {
			return new WP_error( 'batch_task_param_not_set', 'The batch task key ' . $key . ' could not be found.' );
		}
	}

}
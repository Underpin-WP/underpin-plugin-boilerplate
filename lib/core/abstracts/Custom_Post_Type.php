<?php
/**
 * Custom Post Type Abstraction.
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Custom_Post_Type
 * Class Custom Post Type
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */
abstract class Custom_Post_Type extends Feature_Extension {

	/**
	 * The post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string The post type "$type" argument.
	 */
	protected $type = '';

	/**
	 * The post type args.
	 *
	 * @since 1.0.0
	 *
	 * @var array The list of post type args. See https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	protected $args = [];

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Registers the post type.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$registered = register_post_type( $this->type, $this->args );

		if ( is_wp_error( $registered ) ) {
			plugin_name_replace_me()->logger()->log_wp_error( 'error', $registered );
		}
	}

}
<?php
/**
 * Admin Bar Menu Abstraction
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Bar_Menu
 * Handles creating custom admin bar menus
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */
abstract class Admin_Bar_Menu {

	public $children = [];

	public $capability = 'administrator';
	public $id;
	public $args;
	public $position = 500;

	public function __construct( $item_id, $args ) {
		$defaults = [
			'title'  => 'Plugin Name Replace Me',
			'href'   => '#',
			'parent' => false,
			'meta'   => [],
		];

		$this->id   = $item_id;
		$this->args = wp_parse_args( $args, $defaults );
		$this->admin_bar_actions();
	}

	public function user_can_view_menu( $user = false ) {
		if ( false === $user ) {
			$can_view_menu = current_user_can( $this->capability );
		} else {
			$can_view_menu = user_can( $user, $this->capability );
		}

		return $can_view_menu;
	}

	protected function admin_bar_actions() {
		add_action( 'admin_bar_menu', [ $this, 'add_admin_bar' ], $this->position );
	}

	public function add_admin_bar( \WP_Admin_Bar $admin_bar ) {
		if ( $this->user_can_view_menu() ) {
			$args       = $this->args;
			$args['id'] = $this->id;

			$admin_bar->add_menu( $args );

			foreach ( $this->children as $id => $child ) {
				$child['id']     = $id;
				$child['parent'] = $this->id;

				$admin_bar->add_menu( $child );
			}
		}
	}
}
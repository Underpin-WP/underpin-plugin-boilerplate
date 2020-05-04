<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Plugin_Name_Replace_Me\Core\Utilities;


use Plugin_Name_Replace_Me\Core\Abstracts\Admin_Bar_Menu;
use Plugin_Name_Replace_Me\Core\Factories\Debug_Bar_Section;
use Plugin_Name_Replace_Me\Core\Traits\Core_Templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Debug_Bar
 *
 *
 * @since
 * @package
 */
class Debug_Bar extends Admin_Bar_Menu {
	use Core_Templates;

	/**
	 * @inheritDoc
	 */
	public $description = 'This registers the actual debug bar button to the wp admin bar.';

	public $name = "Debug Bar";

	public function __construct() {
		parent::__construct( 'plugin_name_replace_me_debugger', [
			'parent' => 'top-secondary',
			'title'  => 'Plugin Name Replace Me Events',
			'href'   => '#',
			'meta'   => [
				'onclick' => '',
			],
		] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ], 11 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ], 11 );
		add_action( 'shutdown', [ $this, 'render_callback' ] );
	}

	/**
	 * Loads in the debug bar script
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets() {
		plugin_name_replace_me()->scripts()->enqueue( 'debug' );
		plugin_name_replace_me()->styles()->enqueue( 'debug' );
	}

	/**
	 * @inheritDoc
	 */
	protected function get_templates() {
		return [
			'wrapper' => [
				'override_visibility' => 'private',
			],
			'section' => [
				'override_visibility' => 'private',
			],
			'console' => [
				'override_visibility' => 'private',
			],
			'tabs'    => [
				'override_visibility' => 'private',
			],
			'section-menu'    => [
				'override_visibility' => 'private',
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function get_template_group() {
		return 'debug-bar';
	}

	/**
	 * Renders the actual debug bar.
	 */
	public function render_callback() {

		// If this is rest, or feed, don't output the render
		if ( defined( 'WP_CLI' ) || wp_doing_ajax() || wp_doing_cron() || defined( 'REST_REQUEST' ) ) {
			return;
		}

		echo $this->get_template( 'wrapper', [
			'sections' => [
				new Debug_Bar_Section(
					'logged-events',
					plugin_name_replace_me()->logger()->get_request_events(),
					'Logged Events',
					"Here's what was logged during this session."
				),
				new Debug_Bar_Section(
					'registered-items',
					plugin_name_replace_me()->export_registered_items(),
					'Registered Items',
					"Here's what items were registered during this session."
				),
			],
		] );
	}
}
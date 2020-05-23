<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Underpin\Utilities;


use Underpin\Abstracts\Admin_Bar_Menu;
use Underpin\Factories\Debug_Bar_Section;
use Underpin\Traits\Underpin_Templates;
use function Underpin\underpin;
use Underpin\Abstracts\Underpin;

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
	use Underpin_Templates;

	/**
	 * @inheritDoc
	 */
	public $description = 'This registers the actual debug bar button to the wp admin bar.';

	public $name = "Debug Bar";

	public function __construct() {
		parent::__construct( 'underpin_debugger', [
			'parent' => 'top-secondary',
			'title'  => 'Underpin Events',
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
		underpin()->scripts()->enqueue( 'debug' );
		underpin()->styles()->enqueue( 'debug' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_templates() {
		return [
			'wrapper'         => [
				'override_visibility' => 'private',
			],
			'section'         => [
				'override_visibility' => 'private',
			],
			'console'         => [
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

		if ( defined( 'WP_TESTS_DOMAIN' ) || defined( 'WP_CLI' ) || wp_doing_ajax() || wp_doing_cron() || defined( 'REST_REQUEST' )  ) {
			return;
		}
		$events_section = new Debug_Bar_Section(
			'logged-events',
			underpin()->logger()->get_request_events(),
			'Logged Events',
			"Here's what was logged during this session."
		);

		$registered_section = new Debug_Bar_Section(
			'registered-items',
			Underpin::export(),
			'Registered Items',
			"Here's what items were registered during this session."
		);

		echo $this->get_template( 'wrapper', [
			'sections' => [
				$events_section,
				$registered_section,
			],
		] );
	}
}
<?php
/**
 * Class SampleTest
 *
 * @package Dfs_Monitor
 */

use function Underpin\underpin;

require_once underpin()->dir() . 'tests/phpunit/Template_Tests.php';
require_once underpin()->dir() . 'tests/phpunit/Loader_Tests.php';

/**
 * Sample test case.
 */
class Underpin_Admin_Pages extends WP_UnitTestCase {
	use Template_Tests;
	use Loader_Tests;

	public static function wpSetUpBeforeClass() {
		if ( empty( (array) underpin()->admin_pages() ) ) {
			self::markTestSkipped( 'The loader ' . get_class( underpin()->admin_pages() ) . ' does not have anything registered to it.' );
		}
	}

	/**
	 * A single example test.
	 */
	public function test_has_parent_slug() {
		foreach ( $this->get_loader() as $key => $value ) {
			$this->assertNotEmpty( $value->parent_slug, get_class( $value ) . ' is not set properly.' );
		}
	}

	/**
	 * A single example test.
	 */
	public function test_has_capability() {
		foreach ( $this->get_loader() as $key => $value ) {
			$this->assertNotEmpty( $value->capability, get_class( $value ) . ' is not set properly.' );
		}
	}

	/**
	 * A single example test.
	 */
	public function test_has_menu_title() {
		foreach ( $this->get_loader() as $key => $value ) {
			$this->assertNotEmpty( $value->menu_title, get_class( $value ) . ' is not set properly.' );
		}
	}

	/**
	 * A single example test.
	 */
	public function test_has_menu_slug() {
		foreach ( $this->get_loader() as $key => $value ) {
			$this->assertNotEmpty( $value->menu_slug, get_class( $value ) . ' is not set properly.' );
		}
	}

	/**
	 * A single example test.
	 */
	public function test_has_nonce_action() {
		foreach ( $this->get_loader() as $key => $value ) {
			$this->assertNotNull( $value->nonce_action, get_class( $value ) . ' is not set properly.' );
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function get_loader() {
		return underpin()->admin_pages();
	}
}

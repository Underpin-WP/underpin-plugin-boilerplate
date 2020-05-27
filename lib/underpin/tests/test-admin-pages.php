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
	 * A single example test.
	 */
	public function test_sections_use_valid_options_keys() {
		foreach ( $this->get_loader() as $key => $value ) {
			foreach ( $value->sections as $section ) {
				$section = new $section;
				$this->assertInstanceOf( 'Underpin\Factories\Option', underpin()->options()->get( $section->options_key ), get_class( $value ) . ' is not set properly.' );
			}
		}
	}

	/**
	 * A single example test.
	 */
	public function test_sections_have_ids() {
		foreach ( $this->get_loader() as $key => $value ) {
			foreach ( $value->sections as $section ) {
				$section = new $section;
				$this->assertNotEmpty( $section->id, get_class( $value ) . ' is not set properly.' );
			}
		}
	}

	public function test_sections_are_instances_of_section_class() {
		foreach ( $this->get_loader() as $key => $value ) {
			foreach ( $value->sections as $section ) {
				$this->assertInstanceOf( 'Underpin\Abstracts\Admin_Section', new $section, get_class( $value ) . ' is not set properly.' );
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function get_loader() {
		return underpin()->admin_pages();
	}
}

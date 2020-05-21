<?php
/**
 *
 *
 * @since
 * @package
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Template_Tests
 *
 *
 * @since
 * @package
 */
trait Loader_Tests {

	/**
	 * Retrieves the class that houses the template trait for this test case.
	 *
	 * @return mixed
	 */
	abstract protected function get_loader();

	public function test_has_name() {
		foreach ( $this->get_loader() as $key => $value ) {
			$this->assertNotEmpty( $value->name, 'Item ' . $key . ' is missing a name.' );
		}
	}

	public function test_has_description() {
		foreach ( $this->get_loader() as $key => $value ) {
			$this->assertNotEmpty( $value->description, 'Item ' . $key . ' is missing a description.' );
		}
	}

}
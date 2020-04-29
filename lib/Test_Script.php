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
 * Class Test_Script
 *
 *
 * @since
 * @package
 */
class Test_Script extends \Plugin_Name_Replace_Me\Core\Abstracts\Script {

	protected $handle = 'test';

	protected $deps = [ 'jquery' ];

	protected $in_footer = true;

	public $description = 'Test script. Throwaway';

	public $name = "Just another script";


}
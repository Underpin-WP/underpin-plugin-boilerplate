<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Underpin\Utilities;


use Underpin\Abstracts\Style;
use Underpin\Abstracts\Underpin;
use function Underpin\underpin;

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
class Debug_Bar_Style extends Style {

	protected $handle = 'plugin_name_replace_me_debug';

	public $description = 'Styles that make the debug bar interface';

	public $name = "Debug Bar Style";

	public function __construct() {
		$this->src = trailingslashit( underpin()->css_url() ) . 'debugStyle.min.css';
	}

}
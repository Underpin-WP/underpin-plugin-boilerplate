<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Plugin_Name_Replace_Me\Core\Utilities;


use Plugin_Name_Replace_Me\Core\Abstracts\Style;

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


	protected $contexts = [ 'site', 'admin', 'author' ];

	public function __construct() {
		$this->src = PLUGIN_NAME_REPLACE_ME_CSS_URL . 'debugStyle.min.css';
		parent::__construct();
	}

}
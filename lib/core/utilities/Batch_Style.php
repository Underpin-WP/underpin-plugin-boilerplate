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
class Batch_Style extends Style {

	protected $handle = 'plugin_name_replace_me_batch';

	public $description = 'Styles for batch tasks.';

	public $name = "Batch Task Runner Styles";

	public function __construct() {
		$this->src = PLUGIN_NAME_REPLACE_ME_CSS_URL . 'batchStyle.min.css';
		parent::__construct();
	}

}
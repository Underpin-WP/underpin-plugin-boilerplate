<?php
/**
 * Script for batch tasks
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Utilitites
 */


namespace Plugin_Name_Replace_Me\Core\Utilities;


use Plugin_Name_Replace_Me\Core\Abstracts\Script;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Batch_Script
 * Script for batch tasks
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Utilitites
 */
class Batch_Script extends Script {

	protected $handle = 'plugin_name_replace_me_batch';

	protected $deps = [ 'jquery' ];

	public $description = 'Script that handles batch tasks.';

	public $name = "Batch Task Runner Script";

	protected $in_footer = true;

	public function __construct() {
		$this->src = PLUGIN_NAME_REPLACE_ME_JS_URL . 'batch.min.js';
		$this->set_param( 'ajaxUrl', admin_url( 'admin-ajax.php' ) );
		parent::__construct();
	}

}
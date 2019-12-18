<?php
/**
 * Error Event for DFS Monitor
 * @author: Alex Standiford
 * @date  : 12/8/19
 */


namespace Plugin_Name_Replace_Me\Utilities\Events;



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\DFS_Monitor\Abstracts\Event_Log_Type' ) ) {
	return;
}

class Plugin_Name_Replace_Me_Error extends \DFS_Monitor\Abstracts\Event_Log_Type {

	/**
	 * @inheritDoc
	 */
	public function type() {
		return 'plugin_name_replace_me_error';
	}

	/**
	 * @inheritDoc
	 */
	public function description() {
		return "This event logs when an error is logged inside the plugin name replace me plugin.";
	}

	/**
	 * @inheritDoc
	 */
	public function plural_name() {
		return "plugin name replace me errors";
	}

	/**
	 * @inheritDoc
	 */
	public function singular_name() {
		return "plugin name replace me error";
	}
}
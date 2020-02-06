<?php
/**
 * Error Event for DFS Monitor
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Utilities\Events
 */


namespace Plugin_Name_Replace_Me\Utilities\Events;



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\DFS_Monitor\Abstracts\Event_Log_Type' ) ) {
	return;
}

/**
 * Class Plugin_Name_Replace_Me_Error
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Utilities\Events
 */
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
		return __( "This event logs when an error is logged inside the RV Share plugin.", 'plugin-name-replace-me' );
	}

	/**
	 * @inheritDoc
	 */
	public function plural_name() {
		return __( "RV Share errors", 'plugin-name-replace-me' );
	}

	/**
	 * @inheritDoc
	 */
	public function singular_name() {
		return __( "RV Share error", 'plugin-name-replace-me' );
	}
}
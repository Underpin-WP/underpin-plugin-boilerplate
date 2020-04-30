<?php
/**
 * Single Log item instance.
 * Handles output and formatting for log item.
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Core\Factories
 */


namespace Plugin_Name_Replace_Me\Core\Factories;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Log_Item
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Core\Factories
 */
class Log_Item {

	/**
	 * Event code.
	 *
	 * @since 1.0.0
	 *
	 * @var string Event code
	 */
	public $code;

	/**
	 * Message
	 *
	 * @since 1.0.0
	 *
	 * @var string Message.
	 */
	public $message;

	/**
	 * Ref.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed Reference. Usually an id or something related to this item.
	 */
	public $ref;

	/**
	 * Event data.
	 *
	 * @since 1.0.0
	 *
	 * @var array Data.
	 */
	public $data;

	/**
	 * Event type.
	 *
	 * @since 1.0.0
	 *
	 * @var string Event code
	 */
	public $type;

	/**
	 * Log Item Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type    Event log type
	 * @param string $code    The event code to use.
	 * @param string $message The message to log.
	 * @param int    $ref     A reference ID related to this error, such as a post ID.
	 * @param array  $data    Arbitrary data associated with this event message.
	 */
	public function __construct( $type, $code, $message, $ref = null, $data = array() ) {
		$this->type    = $type;
		$this->code    = $code;
		$this->message = $message;
		$this->ref     = $ref;
		$this->data    = $data;
	}

	/**
	 * Formats the event log.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function format() {

		$log_message = $this->code . ' - ' . $this->message;

		if ( $this->ref !== null ) {
			$data['ref'] = $this->ref;
		}

		if ( ! empty( $data ) ) {
			$log_message .= "\n data:" . var_export( (object) $data, true );
		}

		return date( 'm/d/Y H:i' ) . ': ' . $log_message;
	}

	/**
	 * Converts this log item to a WP Error object.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_Error
	 */
	public function error() {
		$data        = $this->data;
		$data['ref'] = $this->ref;

		return new \WP_Error( $this->code, $this->message, $data );
	}
}
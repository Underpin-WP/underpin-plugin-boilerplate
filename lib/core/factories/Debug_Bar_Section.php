<?php
/**
 * Debug Bar Section
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Factories
 */

namespace Plugin_Name_Replace_Me\Core\Factories;

use Plugin_Name_Replace_Me\Core\Traits\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Debug_Bar_Section
 * Class Debug Bar Section
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Factories
 */
class Debug_Bar_Section {

	/**
	 * The list of items (tabs) this section will display.
	 *
	 * @since 1.0.0
	 * @var array list of items keyed by their id.
	 */
	public $items;

	/**
	 * Subtitle to display with this section.
	 *
	 * @var string
	 */
	public $subtitle;

	/**
	 * Title to display with this section.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Unique identifier for this section.
	 *
	 * @var string
	 */
	public $id;

	public function __construct( $id, $items, $title = 'Section Name', $subtitle = '' ) {
		$this->id       = $id;
		$this->items    = $items;
		$this->title    = $title;
		$this->subtitle = $subtitle;
	}

}
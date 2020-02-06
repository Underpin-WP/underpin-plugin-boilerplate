<?php
/**
 * Code Coverage For Basic_Logger
 *
 * @package Plugin_Name_Replace_Me\Utilities
 */

/**
 * @covers Plugin_Name_Replace_Me\Utilities\Basic_Logger
 */
class Basic_Logger_Test extends WP_UnitTestCase {

	public function setUp() {
		// Clear the log
		plugin_name_replace_me()->logger()->wipe();
		plugin_name_replace_me()->logger()->reset_events();
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::log
	 */
	public function test_log_logs_the_error_if_type_is_valid() {
		plugin_name_replace_me()->logger()->log(
			'plugin_name_replace_me_api_event',
			'test_event',
			'Test Event',
			1,
			[ 'data_test' => 'data' ]
		);
		plugin_name_replace_me()->logger()->log(
			'plugin_name_replace_me_api_event',
			'test_event',
			'Test Event',
			1,
			[ 'data_test' => 'data' ]
		);

		$events = plugin_name_replace_me()->logger()->events();

		$this->assertCount( 2, $events['plugin_name_replace_me_api_event'] );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::log
	 */
	public function test_log_returns_wp_error_object() {
		$event = plugin_name_replace_me()->logger()->log( 'plugin_name_replace_me_api_event', '', '' );

		$this->assertInstanceOf( '\WP_Error', $event );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::file
	 */
	public function test_get_log_file_creates_log_file_path_if_type_is_valid() {
		plugin_name_replace_me()->logger()->file( 'plugin_name_replace_me_api_event' );

		$this->assertFileExists( plugin_name_replace_me()->logger()->path( 'plugin_name_replace_me_api_event' ) );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::file
	 */
	public function test_get_log_file_returns_log_file_path_if_type_is_valid() {
		$path = plugin_name_replace_me()->logger()->file( 'plugin_name_replace_me_api_event' );

		$this->assertSame( plugin_name_replace_me()->logger()->path( 'plugin_name_replace_me_api_event' ), $path );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::file
	 */
	public function test_get_log_file_returns_error_if_type_is_invalid() {
		$file = plugin_name_replace_me()->logger()->file( 'invalid_event_type' );

		$this->assertInstanceOf( '\WP_Error', $file );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::path
	 */
	public function test_get_log_path_returns_error_if_type_is_invalid() {
		$file = plugin_name_replace_me()->logger()->path( 'invalid_event_type' );

		$this->assertInstanceOf( '\WP_Error', $file );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::events
	 */
	public function test_events_returns_array_of_event_types() {
		plugin_name_replace_me()->logger()->log(
			'plugin_name_replace_me_api_event',
			'test_event',
			'Test Event',
			1,
			[ 'data_test' => 'data' ]
		);

		$events = array_keys( plugin_name_replace_me()->logger()->events() );

		$this->assertSame( $events, [ 'plugin_name_replace_me_error', 'plugin_name_replace_me_api_event' ] );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::reset_events
	 */
	public function test_reset_events_clears_request_events() {
		plugin_name_replace_me()->logger()->log( 'plugin_name_replace_me_api_event', 'test_event', 'Test Event' );
		plugin_name_replace_me()->logger()->log( 'plugin_name_replace_me_error', 'test_error', 'Test Event' );

		plugin_name_replace_me()->logger()->reset_events();
		$events = plugin_name_replace_me()->logger()->events();
		$test   = array_reduce( $events, 'array_merge', [] );

		$this->assertCount( 0, $test );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::log_events
	 */
	public function test_log_events_should_write_events_to_log_file() {
		plugin_name_replace_me()->logger()->log( 'plugin_name_replace_me_api_event', 'test_event', 'Test Event' );
		plugin_name_replace_me()->logger()->log( 'plugin_name_replace_me_error', 'test_error', 'Test Event' );

		plugin_name_replace_me()->logger()->log_events();

		$files = plugin_name_replace_me()->logger()->files();
		$test  = [
			plugin_name_replace_me()->logger()->path( 'plugin_name_replace_me_api_event' ),
			plugin_name_replace_me()->logger()->path( 'plugin_name_replace_me_error' ),
		];

		$this->assertSame( $files, $test );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::clear_log
	 */
	public function test_clear_log_should_clear_log_files_for_event_type() {
		plugin_name_replace_me()->logger()->log( 'plugin_name_replace_me_api_event', 'test_event', 'Test Event' );

		plugin_name_replace_me()->logger()->log_events();
		plugin_name_replace_me()->logger()->clear( 'plugin_name_replace_me_api_event' );

		$this->assertFileNotExists( plugin_name_replace_me()->logger()->path( 'plugin_name_replace_me_api_event' ) );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::wipe_log
	 */
	public function test_wipe_should_clear_all_log_files() {
		plugin_name_replace_me()->logger()->log( 'plugin_name_replace_me_api_event', 'test_event', 'Test Event' );
		plugin_name_replace_me()->logger()->log( 'plugin_name_replace_me_error', 'test_error', 'Test Event' );

		plugin_name_replace_me()->logger()->log_events();
		plugin_name_replace_me()->logger()->wipe();

		$this->assertEmpty( plugin_name_replace_me()->logger()->files() );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::parse_file
	 */
	public function test_parse_file_should_return_log_file_info_with_path() {
		$path   = plugin_name_replace_me()->logger()->path( 'plugin_name_replace_me_api_event' );
		$parsed = plugin_name_replace_me()->logger()->parse_file( $path );
		$test   = [
			'type' => 'plugin_name_replace_me_api_event',
			'date' => date( 'M-d-Y', strtotime( 'today' ) ),
			'path' => $path,
		];

		$this->assertSame( $parsed, $test );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::parse_file
	 */
	public function test_parse_file_should_return_log_file_info_with_file_name_only() {
		$date   = date( 'M-d-Y', strtotime( 'today' ) );
		$file   = 'plugin-name-replace-me-api-event-log__' . $date . '.log';
		$parsed = plugin_name_replace_me()->logger()->parse_file( $file );
		$path   = plugin_name_replace_me()->logger()->path( 'plugin_name_replace_me_api_event', $date );

		$test = [ 'type' => 'plugin_name_replace_me_api_event', 'date' => $date, 'path' => $path ];

		$this->assertSame( $parsed, $test );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::parse_file
	 */
	public function test_parse_file_should_return_error_if_type_is_invalid() {
		$date   = date( 'M-d-Y', strtotime( 'today' ) );
		$parsed = plugin_name_replace_me()->logger()->parse_file( 'invalid-type__' . $date . '.log' );

		$this->assertInstanceOf( '\WP_Error', $parsed );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::parse_file
	 */
	public function test_parse_file_should_return_error_if_file_is_not_a_log() {
		$date   = date( 'M-d-Y', strtotime( 'today' ) );
		$parsed = plugin_name_replace_me()->logger()->parse_file( 'plugin-name-replace-me-api-event-log__' . $date );

		$this->assertInstanceOf( '\WP_Error', $parsed );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::parse_file
	 */
	public function test_parse_file_should_return_error_if_name_is_malformed() {
		$parsed = plugin_name_replace_me()->logger()->parse_file( 'invalid-type_and_such.log' );

		$this->assertInstanceOf( '\WP_Error', $parsed );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::purge
	 */
	public function test_purge_should_purge_files_older_than_specified_date() {
		// Write an old file of the specified type to the system.
		$test = [ plugin_name_replace_me()->logger()->file( 'plugin_name_replace_me_error', 'today - 2 days' ) ];
		// Purge log
		$purged = plugin_name_replace_me()->logger()->purge( 1 );

		$this->assertSame( $test, $purged );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::purge
	 */
	public function test_purge_should_not_purge_files_newer_than_specified_date() {
		$test = [];
		// Write an old file of the specified type to the system.
		$test[] = plugin_name_replace_me()->logger()->file( 'plugin_name_replace_me_error', 'today - 2 days' );
		$test[] = plugin_name_replace_me()->logger()->file( 'plugin_name_replace_me_error', 'today - 3 days' );
		plugin_name_replace_me()->logger()->file( 'plugin_name_replace_me_error', 'yesterday' );

		// Purge log
		$purged = plugin_name_replace_me()->logger()->purge( 1 );

		$this->assertSame( asort( $test ), asort( $purged ) );
	}

	/**
	 * @covers \Plugin_Name_Replace_Me\Utilities\Basic_Logger::purge
	 */
	public function test_purge_should_return_wp_error_if_purge_is_negative_number() {
		$this->assertInstanceOf( '\WP_Error', plugin_name_replace_me()->logger()->purge( -1 ) );
	}
}
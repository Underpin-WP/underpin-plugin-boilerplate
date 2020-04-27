<?php
/**
 * Admin Page abstraction
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;


use Plugin_Name_Replace_Me\Core\Traits\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Page
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */
abstract class Admin_Page {
	use Templates;

	/**
	 * List of sections.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $sections = [];

	/**
	 * Parent slug for this menu.
	 * If no slug is specified, this menu will still be registered, but it will not appear in the WordPress menu.
	 *
	 * @since 1.0.0
	 *
	 * @var string the parent slug.
	 */
	protected $parent_slug = 'options-general.php';

	/**
	 * The title to display in the admin menu.
	 *
	 * @since 1.0.0
	 *
	 * @var string the menu title.
	 */
	protected $menu_title = 'Plugin Name Replace Me Settings';

	/**
	 * The page title to display on the page.
	 *
	 * @since 1.0.0
	 *
	 * @var string the page title.
	 */
	protected $page_title = 'Plugin Name Replace Me';

	/**
	 * The capability required to visit this admin page.
	 *
	 * @since 1.0.0
	 *
	 * @var string the capability.
	 */
	protected $capability = 'administrator';

	/**
	 * The unique identifier for this menu.
	 *
	 * @since 1.0.0
	 *
	 * @var string the menu slug.
	 */
	protected $menu_slug = 'plugin-name-replace-me';

	/**
	 * The position in the menu order this item should appear.
	 *
	 * @since 1.0.0
	 *
	 * @var int the menu position.
	 */
	protected $position = null;

	/**
	 * The nonce action used to validate when interfacing with this page.
	 *
	 * @since 1.0.0
	 *
	 * @var string the nonce action.
	 */
	protected $nonce_action;

	/**
	 * The key to use when updating options.
	 *
	 * @since 1.0.0
	 *
	 * @var string The options key
	 */
	private $options_key = false;

	/**
	 * Admin_Page constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args List of arguments used to create this menu page.
	 */
	public function __construct() {
		$this->options_key  = false === $this->options_key ? $this->parent_slug . '_settings' : $this->options_key;
		$this->nonce_action = $this->menu_slug . '_nonce';

		/**
		 * Filters the sections and fields for the current admin page.
		 *
		 * @since 1.0.0
		 * @param array $sections List of sections for this admin page.
		 */
		$this->sections = apply_filters( 'plugin_name_replace_me/admin_pages/' . $this->menu_slug . '/sections', $sections );

		$this->update_actions();
		$this->register_actions();
	}

	/**
	 * Function that necessitates saving a single field however it needs to be saved.
	 *
	 * @since 1.0.0
	 *
	 * @param Settings_Field $field The field to save.
	 * @return true|\WP_Error true if the field saved, WP_Error otherwise.
	 */
	public function save_field( Settings_Field $field ) {
		$options_key = $this->options_key;
		$options     = get_option( $options_key );
		// Get the field name.
		$field_name = $field->get_field_param( 'name' );

		// If the field type is a checkbox, update value based on if the field was included.
		if ( 'checkbox' === $field->get_field_type() ) {
			$checked                = isset( $_POST[ $field_name ] );
			$options[ $field_name ] = $field->update_value( $checked );

			// Otherwise, Update the value if the field is provided.
		} elseif ( isset( $_POST[ $field_name ] ) ) {
			$options[ $field_name ] = $field->update_value( $_POST[ $field_name ] );
		}

		$updated = update_option( $options_key, $options );

		if ( true !== $updated ) {
			$updated = plugin_name_replace_me()->logger()->log(
				'plugin_name_replace_me_error',
				'update_request_settings_failed_to_update',
				__( 'The ' . $options_key . ' settings failed to update.' )
			);
		}

		return $updated;
	}

	/**
	 * Retrieves the specified section.
	 *
	 * @since 1.0.0
	 *
	 * @param string $section The section to retrieve. If left blank, this will automatically retrieve from GET.
	 * @return mixed|\WP_Error
	 */
	public function get_section( $section = '' ) {

		if ( '' === $section ) {
			$section = $this->get_current_section_key();
		}

		if ( isset( $this->sections[ $section ] ) ) {
			return $this->sections[ $section ];
		}

		return plugin_name_replace_me()->logger()->log(
			'plugin_name_replace_me_error',
			'no_admin_section_found',
			'No valid section could be found',
			[ 'sections' => $this->sections, 'admin_page' => $this->parent_slug ]
		);
	}

	/**
	 * Retrieves the name of the current section.
	 *
	 * @since 1.0.0
	 *
	 * @return string The current section name, or an empty string.
	 */
	public function get_current_section_key() {
		if ( isset( $_GET['section'] ) && isset( $this->sections[ $_GET['section'] ] ) ) {
			return $_GET['section'];
		}

		$section_names = array_keys( $this->sections );

		if ( ! empty( $section_names ) ) {
			return $section_names[0];
		}

		return '';
	}

	/**
	 * Registers actions to update this admin page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function update_actions() {
		add_action( 'wp_loaded', [ $this, 'handle_update_request' ] );
	}

	/**
	 * Registers actions to register this admin page to WordPress.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_actions() {
		add_action( 'admin_menu', [ $this, 'register_sub_menu' ] );
	}

	/**
	 * Validates this request.
	 *
	 * @since 1.0.0
	 *
	 * @return true|\WP_Error True if request is validated, otherwise WP_Error containing what went wrong.
	 */
	public function validate_request() {
		$errors = new \WP_Error;

		// If this is not an admin page, bail
		if ( ! is_admin() ) {
			$errors->add(
				'update_request_settings_not_admin',
				__( 'An update request attempted to run outside of the admin area.' ),
				[ 'page' => $_SERVER['REQUEST_URI'] ]
			);
		}

		// If this is not the correct settings page, bail
		if ( $this->menu_slug !== $_GET['page'] ) {
			$errors->add(
				'update_request_settings_invalid_settings_page',
				__( 'An update request attempted to run outside of the specified settings settings page.' ),
				[ 'actual_page' => $_GET['page'], 'expected_page' => $this->menu_slug ]
			);
		}

		// If we don't have a nonce, bail.
		if ( ! isset( $_POST['plugin_name_replace_me_nonce'] ) ) {
			$errors->add(
				'update_request_settings_no_nonce',
				__( 'An update request attempted to run without a nonce.' )
			);
		}

		// If the current user can't edit these options, bail
		if ( true !== current_user_can( $this->capability ) ) {
			$errors->add(
				'update_request_settings_invalid_permissions',
				__( 'An update request attempted to run without the privileges.' ),
				[ 'user' => get_current_user_id() ]
			);
		}

		// If the nonce is invalid, bail
		if ( 1 !== wp_verify_nonce( $_POST['plugin_name_replace_me_nonce'], $this->nonce_action ) ) {
			$errors->add(
				'update_request_settings_invalid_nonce',
				__( 'An update requested attempted to run with an invalid nonce.' )
			);
		}

		foreach ( $this->get_section_fields() as $field ) {
			if ( ! $field instanceof Settings_Field ) {
				$errors->add(
					'field_invalid',
					'The provided field is not an instance of a settings field',
					[ 'field' => $field ]
				);
			}
		}

		return $errors->has_errors() ? $errors : true;
	}

	/**
	 * Action to save all fields.
	 *
	 * @since 1.0.0
	 *
	 * @return true|\WP_Error True if all fields were saved, WP_Error containing errors if not.
	 */
	public function save() {
		$errors = new \WP_Error;

		foreach ( $this->get_section_fields() as $field ) {

			$saved = $this->save_field( $field );

			if ( is_wp_error( $saved ) ) {
				$errors->add( $saved->get_error_code(), $saved->get_error_message(), $saved->get_error_data() );
			}
		}

		if ( $errors->has_errors() ) {
			plugin_name_replace_me()->logger()->log(
				'plugin_name_replace_me_error',
				'failed_to_save_settings',
				'some settings failed to save',
				[ 'errors' => $errors ]
			);
		}

		return $errors->has_errors() ? true : $errors;
	}

	/**
	 * Callback to handle update requests from the options screen.
	 *
	 * @since 1.0.0
	 *
	 * @return true|\WP_Error True if saved successfully, otherwise WP_Error.
	 */
	public function handle_update_request() {
		$valid = $this->validate_request();

		if ( is_wp_error( $valid ) ) {
			return $valid;
		}

		return $this->save();
	}


	/**
	 * Retrieves the fields for the specified section.
	 *
	 * @since 1.0.0
	 *
	 * @param string $section The name of the section.
	 * @return array|\WP_Error
	 */
	public function get_section_fields( $section = '' ) {
		$section = $this->get_section( $section );

		if ( is_wp_error( $section ) ) {
			return $section;
		}

		if ( ! isset( $section['fields'] ) ) {
			return new \WP_Error(
				'section_has_no_fields',
				'The specified section has no fields',
				[ 'section' => $section ]
			);
		}

		return $section['fields'];
	}

	/**
	 * Registers sub menus
	 *
	 * @since 1.0.0
	 */
	public function register_sub_menu() {
		add_submenu_page(
			$this->parent_slug,
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			[ $this, 'render_callback' ],
			$this->position
		);
	}

	/**
	 * Callback function to render the actual settings content.
	 *
	 * @since 1.0.0
	 */
	public function render_callback() {

		$template = $this->get_template( 'admin', [
			'title'        => $this->page_title,
			'section'      => $this->get_current_section_key(),
			'sections'     => $this->sections,
			'nonce_action' => $this->nonce_action,
			'menu_slug'    => $this->menu_slug,
		] );
		if ( ! is_wp_error( $template ) ) {
			echo $template;
		}
	}

	/**
	 * Retrieves the URL of the current section.
	 *
	 * @since 1.0.0
	 *
	 * @param string $section The name of the section.
	 * @return string a URL of the specified section of this settings page.
	 */
	public function get_section_url( $section ) {

		$url = get_admin_url();
		$url .= $this->parent_slug;
		$url .= '?page=' . $this->menu_slug;

		if ( isset( $this->sections[ $section ] ) ) {
			$url .= '&section=' . $section;
		}

		return $url;
	}

	/**
	 * Fetches the valid templates and their visibility.
	 *
	 * override_visibility can be either "theme", "plugin", "public" or "private".
	 *  theme   - sets the template to only be override-able by a parent, or child theme.
	 *  plugin  - sets the template to only be override-able by another plugin.
	 *  public  - sets the template to be override-able anywhere.
	 *  private - sets the template to be non override-able.
	 *
	 * @since 1.0.0
	 *
	 * @return array of template properties keyed by the template name
	 */
	protected function get_templates() {
		return [
			'admin'         => [
				'override_visibility' => 'private',
			],
			'admin-section' => [
				'override_visibility' => 'private',
			],
			'admin-heading' => [
				'override_visibility' => 'private',
			],
		];
	}

	/**
	 * Fetches the template group name.
	 *
	 * @since 1.0.0
	 *
	 * @return string The template group name
	 */
	protected function get_template_group() {
		return 'admin';
	}
}
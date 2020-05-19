<?php
/**
 * Plugin Underpin
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin\Abstracts;

use Exception;
use Underpin\Abstracts\Registries\Loader_Registry;
use Underpin\Loaders;
use WP_Error;
use function Underpin\underpin;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Underpin
 * Underpin Class
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */
abstract class Underpin {

	/**
	 * Houses all of the singleton classes used in this plugin.
	 * Not intended to be manipulated directly.
	 *
	 * @since 1.0.0
	 * @var array Array of class instance.
	 */
	private $class_registry = [];

	/**
	 * Base class instances for everything that uses this bootstrap.
	 *
	 * @since 1.0.0
	 * @var Underpin|null The one true instance of the Underpin
	 */
	protected static $instances = [];

	/**
	 * The namespace for loaders. Used for loader autoloading.
	 *
	 * @since 1.0.0
	 *
	 * @var string Complete namespace for all loaders.
	 */
	protected $loader_namespace = "Underpin\Loaders";

	/**
	 * The minimum PHP version for this bootstrap.
	 *
	 * @var string The PHP version
	 */
	protected $minimum_php_version;

	/**
	 * The minimum WP version for this bootstrap.
	 *
	 * @var string The WP version
	 */
	protected $minimum_wp_version;

	/**
	 * The current version of this plugin.
	 *
	 * @var string The plugin version.
	 */
	protected $version;

	/**
	 * The URL to the file for this plugin.
	 * The URL is constructed from the path passed to the constructor.
	 *
	 * @var string The PHP version.
	 */
	protected $url;

	/**
	 * The URL to the css directory for this plugin.
	 * The URL to the css directory is constructed from the path passed to the constructor.
	 * This can be accessed using css_url(), and overridden with the the _setup_params function.
	 *
	 *
	 * @var string The CSS URL. Defaults to $this->url . 'assets/css/build'
	 */
	protected $css_url;

	/**
	 * The URL to the JS directory for this plugin.
	 * The URL to the JS directory is constructed from the path passed to the constructor.
	 * This can be accessed using js_url(), and overridden with the the _setup_params function.
	 *
	 * @var string The JS URL. Defaults to $this->url . 'assets/js/build'
	 */
	protected $js_url;

	/**
	 * The plugin root directory for this plugin.
	 * The directory is constructed from the path passed to the constructor.
	 * This can be accessed using dir(), and overridden with the the _setup_params function.
	 *
	 * @var string The root plugin directory.
	 */
	protected $dir;

	/**
	 * The plugin root file for this plugin.
	 * This is constructed from the path passed to the constructor.
	 * This can be accessed using file(), and overridden with the the _setup_params function.
	 *
	 * @var string The root plugin file.
	 */
	protected $file;

	/**
	 * The plugin root directory for this plugin.
	 * The directory is constructed from the path passed to the constructor.
	 * This can be accessed using dir(), and overridden with the the _setup_params function.
	 *
	 * @var string The template directory. Defaults to $this->dir . 'templates'
	 */
	protected $template_dir;

	/**
	 * Retrieves the minimum PHP version for this plugin.
	 *
	 * @return string
	 */
	public function minimum_php_version() {
		return $this->minimum_php_version;
	}

	/**
	 * Retrieves the minimum WP version for this plugin.
	 *
	 * @return string
	 */
	public function minimum_wp_version() {
		return $this->minimum_wp_version;
	}

	/**
	 * Retrieves the current version of this plugin.
	 *
	 * @return string
	 */
	public function version() {
		return $this->version;
	}

	/**
	 * Retrieves the URL of this plugin.
	 *
	 * @return string
	 */
	public function url() {
		return trailingslashit( $this->url );
	}

	/**
	 * Retrieves the CSS URL of this plugin.
	 *
	 * @return string
	 */
	public function css_url() {
		return trailingslashit( $this->css_url );
	}

	/**
	 * Retrieves the JS URL of this plugin.
	 *
	 * @return string
	 */
	public function js_url() {
		return trailingslashit( $this->js_url );
	}

	/**
	 * Retrieves the root directory of this plugin.
	 *
	 * @return string
	 */
	public function dir() {
		return trailingslashit( $this->dir );
	}

	/**
	 * Retrieves the root file of this plugin.
	 *
	 * @return string
	 */
	public function file() {
		return $this->file;
	}

	/**
	 * Retrieves the template directory of this plugin.
	 *
	 * @return string
	 */
	public function template_dir() {
		return trailingslashit( $this->template_dir );
	}

	/**
	 * Placeholder to set up the plugin.
	 * This function runs once during the runtime, the first time the plugin is instantiated.
	 *
	 * @return mixed
	 */
	abstract protected function _setup();

	/**
	 * Fetches the specified class, and constructs the class if it hasn't been constructed yet.
	 *
	 * @since 1.0.0
	 *
	 * @param $class
	 * @return mixed
	 */
	protected function _get_class( $class ) {
		if ( ! isset( $this->class_registry[ $class ] ) ) {
			if ( class_exists( $class ) ) {
				$this->class_registry[ $class ] = new $class;
			} else {
				$this->class_registry[ $class ] = new WP_Error(
					'class_could_not_be_found',
					'The specified class could not be located',
					[ 'class' => $class ]
				);
			}
		}

		return $this->class_registry[ $class ];
	}

	/**
	 * Retrieves A loader. This is generally used in other methods in this class to instantiate loaders.
	 *
	 * @param string $loader The namespaced loader class to retrieve.
	 * @return mixed
	 */
	protected function _get_loader( $loader ) {
		$class = underpin()->_get_class( 'Underpin\Loaders\\' . $loader );

		// If this is not a core loader, attempt to get it from this plugin.
		if ( is_wp_error( $class ) ) {
			$class = $this->_get_class( $this->loader_namespace . '\\' . $loader );
		}

		return $class;
	}

	/**
	 * Exports registered items to a text-friendly dump. Used by the debug log to display registered items.
	 *
	 * @return array list of registered item exports.
	 */
	public static function export() {
		$results = [];

		foreach ( Underpin::$instances as $key => $instance ) {
			if ( $instance instanceof Underpin ) {
				$results = $instance->export_registered_items( $results );
			}
		}

		return $results;
	}

	/**
	 * Retrieves a list of registered loader items from the registry.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function export_registered_items( $results = [] ) {
		foreach ( $this->class_registry as $key => $class ) {
			if ( $class instanceof Loader_Registry ) {
				if ( ! empty( $class ) ) {
					ob_start();
					foreach ( $class as $registered_key => $registered_class ) {
						echo "******************************";
						if ( isset( $registered_class->name ) ) {
							echo "\n" . $registered_class->name;
							unset( $registered_class->name );
						}
						if ( isset( $registered_class->description ) ) {
							echo "\n" . $registered_class->description;
							unset( $registered_class->description );
						}
						echo "\n" . $registered_key;

						echo "\n******************************\n";

						if ( method_exists( $registered_class, 'export' ) ) {
							var_dump( $registered_class->export() );
						} else {
							var_dump( $registered_class );
						}
					}

					$key             = explode( '\\', $key );
					$key             = array_pop( $key );
					$results[ $key ] = ob_get_clean();
				}
			}
		}

		return $results;
	}

	/**
	 * Sends a notice if the WordPress or PHP version are below the minimum requirement.
	 *
	 * @since 1.0.0
	 */
	public function below_version_notice() {
		global $wp_version;

		if ( version_compare( $wp_version, $this->minimum_wp_version, '<' ) ) {
			echo '<div class="error">
							<p>' . __( sprintf( "Underpin plugin is not activated. The plugin requires at least WordPress %s to function.", $this->minimum_wp_version() ), 'underpin' ) . '</p>
						</div>';
		}

		if ( version_compare( phpversion(), $this->minimum_php_version, '<' ) ) {
			echo '<div class="error">
							<p>' . __( sprintf( "Underpin plugin is not activated. The plugin requires at least PHP %s to function.", $this->minimum_php_version() ), 'underpin' ) . '</p>
						</div>';
		}
	}

	/**
	 * Registers the autoloader.
	 *
	 * @sicne 1.0.0
	 *
	 * @return bool|string
	 */
	protected function _setup_autoloader() {
		try{
			spl_autoload_register( function( $class ) {
				$class = explode( '\\', $class );

				$root = trailingslashit( $this->dir ) . 'lib/';

				array_shift( $class );

				$file_name = array_pop( $class );
				$directory = str_replace( '_', '-', strtolower( implode( DIRECTORY_SEPARATOR, $class ) ) );
				$file      = $root . $directory . '/' . $file_name . '.php';

				if ( file_exists( $file ) ) {
					require $file;

					return true;
				}

				return false;
			} );
		}catch( Exception $e ){
			$this->logger()->log_exception( 'autoload_failed', $e );

			return $e->getMessage();
		}

		return false;
	}

	/**
	 * Fetches the Logger instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Logger
	 */
	public function logger() {
		return $this->_get_loader( 'Logger' );
	}

	/**
	 * Fetches the Options instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Options
	 */
	public function options() {
		return $this->_get_loader( 'Options' );
	}

	/**
	 * Fetches the Batch_Tasks instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Batch_Tasks
	 */
	public function batch_tasks() {
		return $this->_get_loader( 'Batch_Tasks' );
	}

	/**
	 * Fetches the Batch_Tasks instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Decision_Lists
	 */
	public function decision_lists() {
		return $this->_get_loader( 'Decision_Lists' );
	}

	/**
	 * Retrieves the scripts loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Scripts
	 */
	public function scripts() {
		return $this->_get_loader( 'Scripts' );
	}

	/**
	 * Retrieves the cron jobs loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Cron_Jobs
	 */
	public function cron_jobs() {
		return $this->_get_loader( 'Cron_Jobs' );
	}

	/**
	 * Retrieves the blocks loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Blocks
	 */
	public function blocks() {
		return $this->_get_loader( 'Blocks' );
	}

	/**
	 * Retrieves the admin bar menus loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Admin_Bar_Menus
	 */
	public function admin_bar_menus() {
		return $this->_get_loader( 'Admin_Bar_Menus' );
	}

	/**
	 * Retrieves the rest endpoints loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Rest_Endpoints
	 */
	public function rest_endpoints() {
		return $this->_get_loader( 'Rest_Endpoints' );
	}

	/**
	 * Retrieves the custom post types loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Custom_Post_Types
	 */
	public function custom_post_types() {
		return $this->_get_loader( 'Custom_Post_Types' );
	}

	/**
	 * Retrieves the taxonomies loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Taxonomies
	 */
	public function taxonomies() {
		return $this->_get_loader( 'Taxonomies' );
	}

	/**
	 * Retrieves the shortcodes loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Shortcodes
	 */
	public function shortcodes() {
		return $this->_get_loader( 'Shortcodes' );
	}

	/**
	 * Retrieves the widgets loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Widgets
	 */
	public function widgets() {
		return $this->_get_loader( 'Widgets' );
	}

	/**
	 * Retrieves the admin_pages loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Admin_Pages
	 */
	public function admin_pages() {
		return $this->_get_loader( 'Admin_Pages' );
	}

	/**
	 * Retrieves the Styles loader.
	 *
	 * @since 1.0.0
	 *
	 * @return Loaders\Styles
	 */
	public function styles() {
		return $this->_get_loader( 'Styles' );
	}

	/**
	 * Checks if the PHP version meets the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 */
	public function supports_php_version() {
		return version_compare( phpversion(), $this->minimum_php_version, '>=' );
	}

	/**
	 * Checks if the WP version meets the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 */
	public function supports_wp_version() {
		global $wp_version;

		return version_compare( $wp_version, $this->minimum_wp_version, '>=' );
	}

	/**
	 * Checks if all minimum requirements are met.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 */
	public function plugin_is_supported() {
		return $this->supports_wp_version() && $this->supports_php_version();
	}

	/**
	 * A set of actions that run when this plugin does not meet the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function unsupported_actions() {
		global $wp_version;

		self::$instances[ __CLASS__ ] = new WP_Error(
			'minimum_version_not_met',
			__( sprintf(
				"The Underpin plugin requires at least WordPress %s, and PHP %s.",
				$this->minimum_wp_version,
				$this->minimum_php_version
			), 'underpin' ),
			array( 'current_wp_version' => $wp_version, 'php_version' => phpversion() )
		);

		add_action( 'admin_notices', array( $this, 'below_version_notice' ) );
	}

	/**
	 * Actions that run when this plugin meets the specified minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function setup() {

		// Set up the autoloader for everything else.
		$this->_setup_autoloader();

		/**
		 * Fires just before the bootstrap starts up.
		 *
		 * @since 1.0.0
		 */
		do_action( 'underpin/before_setup', get_called_class() );


		// Set up classes that register things.
		$this->_setup();

		/**
		 * Fires just after the bootstrap is completely set-up.
		 *
		 * @since 1.0.0
		 */
		do_action( 'underpin/after_setup', get_called_class() );
	}

	protected function _setup_params( $file ) {

		// Root file for this plugin. Used in activation hooks.
		$this->file = $file;

		// The URL for this plugin. Used in asset loading.
		$this->url = plugin_dir_url( $file );

		// Root directory for this plugin.
		$this->dir = plugin_dir_path( $file );

		// The CSS URL for this plugin. Used in asset loading.
		$this->css_url = $this->url . 'assets/css/build';

		// The JS URL for this plugin. Used in asset loading.
		$this->js_url = $this->url . 'assets/js/build';

		// The template directory. Used by the template loader to determine where templates are stored.
		$this->template_dir = $this->dir . 'templates/';
	}

	/**
	 * Fires up the plugin.
	 *
	 * @since        1.0.0
	 *
	 * @param string $file The complete path to the root file in this plugin. Usually the __FILE__ const.
	 * @return self
	 * @noinspection PhpUndefinedMethodInspection
	 */
	public function get( $file ) {
		$class = get_called_class();
		if ( ! isset( self::$instances[ $class ] ) ) {
			$this->_setup_params( $file );

			// First, check to make sure the minimum requirements are met.
			if ( $this->plugin_is_supported() ) {
				self::$instances[ $class ] = $this;

				// Setup the plugin, if requirements were met.
				self::$instances[ $class ]->setup();

			} else {
				// Run unsupported actions if requirements are not met.
				$this->unsupported_actions();
			}
		}

		return self::$instances[ $class ];
	}
}
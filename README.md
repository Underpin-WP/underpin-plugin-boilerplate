# Boilerplate for Custom Projects

This boilerplate is designed to work well for large, complex WordPress plugins. The goal of this system is to create an
opinionated pattern for creating a plugin in WordPress.

## Service Provider

Absolutely everything, except for one-off helper functions in `functions.php` is encapsulated in a singleton-instance
service provider. A key benefit to this pattern is that only _necessary_ components of the plugin get loaded into
WordPress on each server request. This is because the service provider will only require, and set up a class when it is
called to do so.

This service provider also serves as a "directory" of sorts, where a third-party developer can easily see all of the
places in which they can interact with the plugin.

## Loaders

A frustrating thing about WordPress is the myriad number of ways things get "added". Everything works _just a little
differently_, and this means a lot of time is spent looking up "how do I do that, again?"

Loaders make it so that everything uses an identical pattern to add items to WordPress. With this system, all of these
things use nearly _exact_ same set of steps to register:

1. Shortcodes
1. Scripts
1. Styles
1. Widgets
1. Cron Jobs
1. REST Endpoints
1. Admin Submenu Pages
1. Menu bar Items

It doesn't support these right now, but it will someday:

1. Post Types
1. Customizer Items
1. Block

It is also fairly straightforward to create custom loaders, so if you have your own extend-able registry of items, you
can add those as well.

The fundamental steps to registering anything with this boilerplate is:

1. Make sure the registry is running. (By default, they're all "off" until you turn them on)
1. Create a class that extends the item you want to register.
1. Add the class to the loader registry.

Learn more about Loaders [under "Registering Things"](#registering-things).

## Template System Trait

This plugin also includes a powerful template system. This system clearly separates HTML markup from business logic, and
provides ways to do things like set default params for values, and declare if a template should be public or private.

## Event Logging Utility

This plugin includes a utility that makes it possible to log events in this plugin. These logs are written to files in
the `wp_uploads` directory, and comes equipped with a cron job that automatically purges old logs.

It also has the capability of using the DFS logging system, if that plugin is installed.

## Webpack

It comes with a fairly modern webpack config that is tailored for WordPress.

## Admin Field Builder

One _powerful_ feature this plugin comes with is a series of pre-built settings fields classes. When used
with the template loader, these fields make it easy to generate form fields using the `place` method.

## Initial Setup

1. Clone this repo, and delete the `.git` directory. Since this is a boilerplate, it's not intended to be updated by the source once cloned.
1. Replace `PLUGIN_NAME_REPLACE_ME` with the abbreviation of your plugin, using UPPER_CASE format.
1. Replace `Plugin_Name_Replace_Me` with the abbreviation of your plugin, using Upper_Snake_Case format.
1. Replace `plugin-name-replace-me` with the abbreviation of your plugin, using lower-dash-case format.
1. Replace `plugin_name_replace_me` with the abbreviation of your plugin, using snake_case format.
1. Replace `plugin name replace me` with the abbreviation of your plugin, using Plugin Name format.
1. (Optional) Open `bootstra.php` and change the constants as-necessary.
1. Start writing.

## Registering Things

A lot of time is spent when WordPress plugins just _registering stuff_ - basically copypasting the scaffolding used to do things like `add_shortcode`, and `register_rest_route`. One key focus of this boilerplate is to better-standardize how this is done across the board.

To register just about anything, the process looks like this:

1. Create a class in the appropriate directory (eg: for shortcodes, use the `lib/shortcode` directory)
1. Extend the abstract class.
1. Fill out the class methods as-needed.

Example - Create a rest route to give the current time.

### Step 1 - Create the file:

**File:** `lib/rest/time/current.php`
```php

<?php

namespace Example_Plugin\Rest\Time;

use Example_Plugin\Abstracts\Rest_Endpoint;
use WP_Rest_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Get_Server_Time extends Rest_Endpoint{

	public function __construct() {
		parent::__construct( 'time/current', [ 'GET', 'POST' ] );
	}

	/**
	 * Endpoint callback.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Rest_Request $request The request object.
	 * @return mixed
	 */
    function endpoint( WP_Rest_Request $request ){
       return ['currentTime' => current_time( 'mysql' )]; 
    }
}

?>
```

### Step 2 - Create loader, or activate it.

This plugin comes pre-packaged with some common loaders, which need to be "activated" to use.

To activate, simply un-comment the loader you wish you use inside the `Service_Locator.php`'s `_setup_classes` method.

Alternatively, if you need to create a custom loader, you would create the class and then instantiate in `_setup_classes`.

```php
<?php
//...

    private function _setup_classes() {
        // Cron Job Registry
        new Initializers\Cron_Jobs;

        // REST Endpoints
        new Initializers\Rest_Endpoints;

        // Shortcodes
        // new Initializers\Shortcodes;

        // Widgets
        // new Initializers\Widgets;
    }

//...
?>
``` 

### Step 3 - Register item inside appropriate loader

In this case, we would simply use the `add` method to add a new end point in the `Rest_Endpoints` loader.
Loaders classes can be found in `lib/registries/loaders`.

```php

<?php
use Plugin_Name_Replace_Me\Abstracts\Registries\Loader_Registry;
//...
	class Rest_Endpoints extends Loader_Registry {
    //...
    protected function set_default_items(){
      $this->add('time', 'Example_Plugin\Rest\Time\Get_Server_Time');
    }
    //...
  }
//...
?>
```

## Working With Scripts

This boilerplate comes with baked-in support for working with scripts and styles. This uses the same pattern as any other
loader registry. To register a script, you need to do the following:

1. Add an entry point for your script in `webpack.config.js`
1. Create a new Script, or Style loader
1. Add the loader to the appropriate registry.
1. Enqueue with `plugin_name_replace_me()->scripts()->enqueue('script-handle')`

### 1.) Add an entry point

First off, you need to add the entry point to the `webpack.config.js` file.

```js
// webpack.config.js
//...
	entry: {
		// JS.
		scriptName: './assets/js/src/script-name.js'
	}
//...
```

### 2.) Create the PHP Loader

Create a new loader in `lib/loaders/scripts/` directory. You can pass the same params through `parent::__construct()` as
what is typically passed to `wp_register_script`, however you only _need_ to specify the `handle`.

If no path is specified, it will automatically assume the path to the script is:
`./assets/js/build/HANDLE.min.js`

So, as long as your `handle` matches what you specify for the `entry` in `webpack.config.js`, you need not
specify a path at all.

You can also specify what should be passed to the Javascript via `wp_localize_script`. This is done by returning an
array of values in `get_localized_params`.

The script is localized just before it is enqueued. If you need to localize earlier, you can manually run
`Script::localize()` at any time.

```php
<?php

namespace Plugin_Name_Replace_Me\Loaders\Scripts;


use Plugin_Name_Replace_Me\Abstracts\Script;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Test_Script extends Script {

	public function __construct() {
		parent::__construct( 'scriptName' );
	}

	public function get_localized_params() {
		return [
			'scriptName' => 'This goes to the javascript',
		];
	}

}

```

## Register the script in `registries/loaders/Scripts.php`

Register the script in `set_default_items`.

```php
<?php
//...

	/**
	 * @inheritDoc
	 */
	protected function set_default_items() {
        // Registers the test script
		$this->add( 'scriptName', '\Plugin_Name_Replace_Me\Loaders\Scripts\Test_Script' );
	}

//...
```

## Working With Styles

Styles work in the exact same fashion as scripts. The only difference is you work with the `Styles` abstraction and the
`Styles` loader registry.

## Autoloader

This boilerplate includes a basic autoloading system. By default, the namespace will represent the subdirectories within
the `lib` directory of the plugin.

For Example, any file with `namespace Example_Plugin\Cron` would need to be located in `lib/cron/`.

As long as your namespaces line up, and you utilize the registries in the manners detailed in this document, you should
_never_ need to manually require a file.

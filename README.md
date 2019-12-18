# Boilerplate for Custom Projects

Boilerplates change all the time, but this is what I'm using right now. Supports an autoloading syntax, and works to standardize methods to register just about anything in WordPress.

## Initial Setup

Do two find/replaces.

1. Clone this repo, and delete the `.git` directory. Since this is a boilerplate, it's not intended to be updated by the source once cloned.
1. Replace `PLUGIN_NAME_REPLACE_ME` with the abbreviation of your plugin, using UPPER_CASE format.
1. Replace `Plugin_Name_Replace_Me` with the abbreviation of your plugin, using Upper_Snake_Case format.
1. Replace `plugin-name-replace-me` with the abbreviation of your plugin, using lower-dash-case format.
1. Replace `plugin_name_replace_me` with the abbreviation of your plugin, using snake_case format.
1. Replace `plugin name replace me` with the abbreviation of your plugin, using Plugin Name format.
1. Rename the file name located in `lib/utilities/events/Plugin_Name_Replace_Me_Error.php` to match the file's class name.
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

### Step 2 - Register in `_setup_classes` method of `boilerplate.php`

```php

<?php
//...
	class Example_Plugin {
    //...
    public function _setup_classes(){
      // Rest endpoints go here
      new Rest\Time\Get_Server_Time;
    }
    //...
  }
//...
?>
```

## Autoloader

This boilerplate includes a basic autoloading system. By default, the namespace will represent the subdirectories within the `lib` directory of the plugin.

For Example, any file with `namespace Example_Plugin\Cron` would need to be located in `lib/cron/`.

## Controllers

A controller class is intended to be a singleton instance class that provides access to internal APIs. Typically, a controller is the plural form of any factory, where a factory is a single instance of something, and a controller interacts with instances of that thing.

These classes are autoloaded by adding a method to the base file using the `_get_class` method.

### Creating a controller class

1. Create the class inside `lib/controllers`. Don't forget to namespace it as `Plugin_Example\Controllers`
1. In `boilerplate.php` create a method that will call the `_get_class` method to call your class.

Example - Create a controller that gets the current time.

## Step 1 - Create the class.
```php
<?php

namespace Example_Plugin\Controllers;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Time {

	/**
	 * Gets the current time
	 * 
	 * @since 1.0.0
	 * 
	 * @return int|string
	 */
	public function get_mysql_time() {
		return current_time( 'mysql' );
	}

}
```


### Step 2 - Make instance accessible by adding a new method to `boilerplate.php`

```php

<?php
//...
	class Example_Plugin {
    //...

    /**
     * Fetches the Time instance.
     *
     * @since 1.0.0
     *
     * @return Controllers\Time
     */
    public function time(){
      return $this->_get_class("Controllers\\Time");
    }

    //...
  }
//...
?>
```

### Step 3 - Use use the class method like so:

`example_plugin()->time()->get_time()`

You could update the rest endpoint example above as:

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
       return ['currentTime' => example_plugin()->time()->get_time()]; 
    }
}

?>
```
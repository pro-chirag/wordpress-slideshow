<?php
/**
 * Plugin manifest class.
 *
 * @package wordpress-slideshow
 */

namespace WordPress_Slideshow\Inc;

use \WordPress_Slideshow\Inc\Traits\Singleton;

/**
 * Class Plugin
 */
class Plugin {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		// Load plugin classes.
		Assets::get_instance();
		WordPress_Slideshow::get_instance();

	}

}

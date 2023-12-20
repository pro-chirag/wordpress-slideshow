<?php
/**
 * Plugin Name: WordPress Slideshow
 * Description: WordPress Slideshow is a slideshow plugin with lots of features that can iteratively cycle through a set of links-accompanied images.
 * Plugin URI:  https://github.com/rtlearn/wpcs-pro-chirag
 * Author:      pro-chirag
 * Author URI:  https://github.com/pro-chirag
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.0
 * Text Domain: wordpress-slideshow
 *
 * @package wordpress-slideshow
 */

define( 'WP_SLIDE_VERSION', '1.0' );
define( 'WP_SLIDE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WP_SLIDE_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

require_once WP_SLIDE_PATH . '/inc/helpers/autoloader.php';

/**
 * To load plugin manifest class.
 *
 * @return void
 */
function wordpress_slideshow_plugin_loader() {
	\WordPress_Slideshow\Inc\Plugin::get_instance();
}
wordpress_slideshow_plugin_loader();
<?php
/**
 * Assets class.
 *
 * @package wordpress-slideshow
 */

namespace WordPress_Slideshow\Inc;

use WordPress_Slideshow\Inc\Traits\Singleton;

/**
 * Class Assets
 */
class Assets {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		/**
		 * Action
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}


	/**
	 * To enqueue scripts and styles. in admin.
	 *
	 * @param string $hook_suffix Admin page name.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		if ( isset( $_GET['page'] ) && 'wordpress-slideshow' === $_GET['page'] ) {

			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-sortable');

			// WordPress media uploader scripts
			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			// Plugin Admin jQuery
			$file_path = WP_SLIDE_PATH . '/assets/js/admin.js';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}
			wp_enqueue_script( 'wp-slide-admin', WP_SLIDE_URL . '/assets/js/admin.js', array('jquery'), $time, true );


			// jQuery UI Library CSS
			$file_path = WP_SLIDE_PATH . '/assets/css/lib/jquery-ui.min.css';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}
			wp_enqueue_style( 'jquery-ui-wp-slide', WP_SLIDE_URL . '/assets/css/lib/jquery-ui.min.css', array(), $time );
			
			// Plugin Admin CSS
			$file_path = WP_SLIDE_PATH . '/assets/css/admin.css';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}
			wp_enqueue_style( 'wp-slide-admin-style', WP_SLIDE_URL . '/assets/css/admin.css', array('jquery-ui-wp-slide'), $time );

		}
	}

	/**
	 * To enqueue scripts and styles. in front.
	 *
	 * @return void
	 */
	public function wp_enqueue_scripts() {

		// Shortcode slideshow - lib slick css
		wp_register_style( 'wp-slide-slick-front', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1', 'all' );
		
		// Plugin Admin CSS
		$file_path = WP_SLIDE_PATH . '/assets/css/admin.css';
		$time      = time();
		if ( file_exists( $file_path ) ) {
			$time = filemtime( $file_path );
		}
		wp_register_style( 'wp-slide-front-style', WP_SLIDE_URL . '/assets/css/front.css', array(), $time, 'all' );
		
		// Shortcode slideshow - lib slick js
		wp_register_script( 'wp-slide-slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true );

		// Shortcode slideshow - general
		$file_path = WP_SLIDE_PATH . '/assets/js/front.js';
		$time      = time();
		if ( file_exists( $file_path ) ) {
			$time = filemtime( $file_path );
		}
		wp_register_script( 'wp-slide-slideshow', WP_SLIDE_URL . '/assets/js/front.js', array('jquery'), $time, true );
	}
}
<?php
/**
 * WordPress Slideshow class definition.
 *
 * @package wordpress-slideshow-opts
 */

namespace WordPress_Slideshow\Inc;

use WordPress_Slideshow\Inc\Traits\Singleton;

/**
 * Class WordPress_Slideshow
 * Create admin page for save settings and options and used for the operation.
 * Enqueues necessary scripts on admin side.
 */
class WordPress_Slideshow {

	use Singleton;

	/**
	 * Holds options and it's required fields.
	 *
	 * @var array
	 */
	private $options = array(
		'LastActiveTab' => 0,
		'wp_slide_imgs'     => array(),
	);

	/**
	 * Holds options.
	 *
	 * @var string
	 */
	private $option = 'wp_slide_options';

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
		add_action('admin_menu', array($this, 'register_menu_page_wp_slide'));
		add_shortcode('myslideshow', array($this, 'shortcode_wp_slide'));

	}

	/**
	 * Admin submenu page under settings.
	 *
	 * @return void
	 */
	public function register_menu_page_wp_slide() {
		add_submenu_page(
			'options-general.php', 'WordPress Slideshow', 'WordPress Slideshow', 'manage_options', 'wordpress-slideshow', array($this, 'submenu_page_wp_slide')
		);

		register_setting( 'wp_slide_setting_group', $this->option );

		add_settings_section(
			'wp_slide_setting_section',
			__( 'Slideshow Gallery LITE.' ), 
			array($this, 'wp_slide_setting_section_callback'),
			'wp_slide_setting_group'
		);
		
		add_settings_field( 
			'wp_slide_fields_for_admin', 
			'All Options',
			array($this, 'wp_slide_fields_for_admin_callback'), 
			'wp_slide_setting_group', 
			'wp_slide_setting_section'
		);
	}

	/**
	 * Admin submenu page render HTML.
	 *
	 * @return void
	 */
	public function submenu_page_wp_slide() {
		if ( !current_user_can('manage_options') ) {
			wp_die( esc_html( 'You dont have enough permissions to view this page.' ) );
		}

		?>
		<div class="wp_slide_main">
			<h1 id="settings_wp_slide_title"><?php esc_html_e('WordPress Slideshow','wordpress-slideshow'); ?></h1>
			<form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "wporg"
				settings_fields( 'wp_slide_setting_group' );
				do_settings_sections( 'wp_slide_setting_group' );
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}


	/**
	 * Custom fields Section.
	 *
	 * @return void
	 */
	public function wp_slide_setting_section_callback() {
		// https://developer.wordpress.org/plugins/settings/custom-settings-page/
	}

	/**
	 * Custom fields HTML.
	 *
	 * @return void
	 */
	public function wp_slide_fields_for_admin_callback() {

		$setting = get_option( $this->option, $this->options );
		?>
		<input type="hidden" name="<?php echo esc_attr($this->option); ?>[LastActiveTab]" id="LastActiveTab" value="<?php echo isset( $setting['LastActiveTab'] ) ? esc_attr( $setting['LastActiveTab'] ) : ''; ?>">
		<div id="tabs">
			<ul>
				<li><a href="#generate-shortcode"><span class="dashicons dashicons-admin-generic"></span>&nbsp;<?php esc_html_e('Generate Shortcode','wordpress-slideshow'); ?></a></li>
				<li><a href="#general-options"><span class="dashicons dashicons-admin-tools"></span>&nbsp;<?php esc_html_e('Settings','wordpress-slideshow'); ?></a></li>
			</ul>
			<div class="wp-slide-tab-wrapper">
				<div id="generate-shortcode">
					<p><?php esc_html_e('Use this shordcode to display the slideshow:','wordpress-slideshow') ?><code>[myslideshow]</code></p>
					<div class="wp-slide-grid">
						<?php $image_ids = isset($setting['wp_slide_imgs']) ? $setting['wp_slide_imgs'] : []; ?>
						<?php foreach ($image_ids as $image_id) : ?>
							<?php $image = wp_get_attachment_image_url( $image_id, 'medium' ); ?>
							<?php if ( $image ) : ?>
								<div class="wp-slide-grid-item">
									<img src="<?php echo esc_url( $image ) ?>" />
									<input type="hidden" name="<?php echo esc_attr($this->option); ?>[wp_slide_imgs][]" value="<?php echo absint($image_id) ?>">
									<span class="wp-slide-remove ui-icon ui-icon-closethick"></span>
								</div>
							<?php endif; ?>

						<?php endforeach; ?>
					</div>
					<div class="wp-slide-btn-box">
						<button class="wp-slide-upload button button-primary" data-option="<?php echo esc_attr($this->option); ?>"><?php esc_html_e('Add','wordpress-slideshow') ?></button>
						<span><?php esc_html_e('You can add multiple images by click this button','wordpress-slideshow') ?></span>
					</div>
				</div>
				<div id="general-options">
					<div class="wrap">
						<?php esc_html_e('Coming with the next version.','wordpress-slideshow') ?>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Creating WordPress Shortcode for display the slideshow.
	 *
	 * @param string $atts Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return void
	 */
	public function shortcode_wp_slide( $atts, $content = '' ) {

		$attributes = shortcode_atts( array(
			'limit' => 20,
		), $atts );

		wp_enqueue_style('wp-slide-slick-front');
		wp_enqueue_style('wp-slide-front-style');
		wp_enqueue_script('wp-slide-slick');
		wp_enqueue_script('wp-slide-slideshow');
		
		ob_start();

		$setting = get_option( $this->option, $this->options ); ?>

		<div class="wp-slide-slideshow">

			<?php $image_ids = isset( $setting['wp_slide_imgs'] ) ? $setting['wp_slide_imgs'] : []; ?>

			<?php if ( empty( $image_ids ) ) : ?>

				<div class="wp-slide-slideshow-item">
					<p><?php esc_html_e('Slideshow - There no any item.','wordpress-slideshow') ?></p>
				</div>

			<?php else : ?>

				<?php

				$i = 0;
				foreach ( $image_ids as $image_id ) : 

					$i++;
					if ( $i > $attributes['limit'] ) { 
						break; 
					}

					$image = wp_get_attachment_image_url( $image_id, 'full' );
					if ( $image ) : ?>

						<div class="wp-slide-slideshow-item">
							<?php

							$image_html = sprintf( '<img alt="%1$s" src="%2$s">', esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true) ), esc_url( $image ) );

							/**
							 * Hook to filter image HTML.
							 *
							 * @since 1.0
							 *
							 * @param int $image_id Attachment ID.
							 *
							 * @return string Image HTML.
							 */
							$image_html = apply_filters( 'wp_slide_slidreshow_image_html', $image_html, $image_id );

							echo wp_kses_post( $image_html );
							?>
						</div> <?php

					endif;

				endforeach; ?>

			<?php endif; ?>

		</div><?php

		return ob_get_clean();
	}

	/**
	 * Function to set transient data.
	 *
	 * @param int   $opts_key Save All Options here.
	 * @param array $data Data to be stored in transient.
	 *
	 * @return void
	 */
	private function cache_wp_slide_data( $opts_key, $data ) {
		set_transient( $this->meta_key . '-' . $item_id, $data, DAY_IN_SECONDS );
	}
}
<?php
/**
 * my-custom-background.php
 *
 * The custom background script.
 * "Shiword_Custom_Background" class based on WP wp-admin/custom-background.php
 *
 * @package Shiword
 * @since 3.0
 */


add_action( 'after_setup_theme', 'shiword_custom_background_init' );


// set up custom colors and header image
function shiword_custom_background_init() {

	if ( shiword_get_opt('shiword_custom_bg' ) ) {

		add_action( 'wp_head',			'shiword_custom_background_plus_style' );
		add_action( 'admin_bar_menu',	'shiword_custom_background_admin_bar', 998 );

		if ( is_admin() )
			$custom_background = new Shiword_Custom_Background(); // the enhanced 'custom background' support

	} else {

		// the standard 'custom background' support
		$args = array(
			'default-color'				=> '',
			'default-image'				=> '',
			'wp-head-callback'			=> 'shiword_custom_background_style',
			'admin-head-callback'		=> '',
			'admin-preview-callback'	=> ''
		);
		add_theme_support( 'custom-background', $args );

	}

}


function shiword_custom_background_style() {

	if ( shiword_is_printpreview() || shiword_is_mobile() ) return;

	$color = get_background_color();

	if ( ! $color ) return;

?>
	<style type="text/css"> 
		body { background-color: #<?php echo trim( $color ); ?>; }
		#head_cont { background-color: #<?php echo trim( $color ); ?>; }
	</style>
<?php

}


function shiword_custom_background_plus_style() {

	if ( shiword_is_printpreview() || shiword_is_mobile() ) return;

	$background = get_background_image();
	$color = get_background_color();

	if ( ! $background && ! $color ) return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background )
		$style .= " background-image: url('$background');";

?>
	<style type="text/css"> 
		body { <?php echo trim( $style ); ?> }
		#fixedfoot_cont { <?php echo trim( $style ); ?> }
		#head_cont { background-color: #<?php echo trim( $color ); ?>; }
	</style>
<?php

}


// add custom background link to admin bar
function shiword_custom_background_admin_bar() {
	global $wp_admin_bar;

	if ( !current_user_can( 'edit_theme_options' ) || !is_admin_bar_showing() )
		return;

	$add_menu_meta = array(
		'target'	=> '_blank'
	);

	$wp_admin_bar->add_menu( array(
		'id'		=> 'shiword_custom_background',
		'parent'	=> 'appearance',
		'title'	 => __( 'Background', 'shiword' ),
		'href'	  => get_admin_url() . 'themes.php?page=custom-background',
		'meta'	  => $add_menu_meta
	) );

}


/**
 * The custom background class.
 *
 */
class Shiword_Custom_Background {

	/**
	 * Holds the page menu hook.
	 */
	var $page = '';

	
	/**
	 * Holds default background images.
	 */
	var $default_bg_images = array();

	
	/**
	 * Constructor - Register administration header callback.
	 */
	function __construct() {

		add_action( 'admin_menu', array( $this, 'init' ) );

	}


	/**
	 * Set up the hooks for the Custom Background admin page.
	 */
	function init() {

		if ( ! current_user_can( 'edit_theme_options' ) )
			return;

		$this->page = $page = add_theme_page( __( 'Background', 'shiword' ), __( 'Background', 'shiword' ), 'edit_theme_options', 'custom-background', array( $this, 'admin_page' ) );

		add_action( "load-$page", array( $this, 'admin_load' ) );
		add_action( "load-$page", array( $this, 'take_action' ) );

	}


	/**
	 * Set up the enqueue for the CSS & JavaScript files.
	 */
	function admin_load() {

		wp_enqueue_script( 'shiword-custom-background', get_template_directory_uri() . '/js/admin-custom_background.dev.js', array( 'jquery' ), '', true  );
		wp_enqueue_style( 'shiword-admin-custom-background', get_template_directory_uri() . '/css/admin-custom_background.css', false, '', 'screen' );
		wp_enqueue_script('custom-background');
		wp_enqueue_style('wp-color-picker');
	}

	
	/**
	 * Execute custom background modification.
	 */
	function take_action() {

		if ( empty($_POST) )
			return;

		if ( isset($_POST['remove-background']) ) {
			check_admin_referer('custom-background-remove', '_wpnonce-custom-background-remove');
			set_theme_mod('background_image', '');
			set_theme_mod('background_image_thumb', '');
			$this->updated = true;
			wp_safe_redirect( $_POST['_wp_http_referer'] );
			return;
		}

		if ( isset($_POST['background-color']) ) {
			check_admin_referer('custom-background');
			$color = preg_replace('/[^0-9a-fA-F]/', '', $_POST['background-color']);
			if ( strlen($color) == 6 || strlen($color) == 3 )
				set_theme_mod('background_color', $color);
			else
				set_theme_mod('background_color', '');
		}

		if ( isset( $_POST['default-bg'] ) ) {

			check_admin_referer( 'custom-background' );
			$this->process_default_bg_images();
			if ( in_array( $_POST['default-bg'], array( 'bamboo', 'flowers', 'equalizer', 'negative', 'city') ) ) {
				set_theme_mod( 'background_image', esc_url( $this->default_bg_images[$_POST['default-bg']]['url'] ) );
				set_theme_mod( 'background_image_thumb', esc_url( $this->default_bg_images[$_POST['default-bg']]['thumbnail_url'] ) );
			}

		}

		$this->updated = true;
	}


	/**
	 * Process the default backgrounds.
	 */
	function process_default_bg_images() {

		$default_bg_images = array(

			'bamboo' => array(
				'url' => '%s/images/backgrounds/bamboo.png',
				'thumbnail_url' => '%s/images/backgrounds/bamboo-thumbnail.jpg',
				'description' => __( 'Bamboo', 'shiword' ),
				'color' => '#E5E2CC'
			),

			'flowers' => array(
				'url' => '%s/images/backgrounds/flowers.png',
				'thumbnail_url' => '%s/images/backgrounds/flowers-thumbnail.jpg',
				'description' => __( 'Flowers', 'shiword' ),
				'color' => '#404040'
			),

			'equalizer' => array(
				'url' => '%s/images/backgrounds/equalizer.png',
				'thumbnail_url' => '%s/images/backgrounds/equalizer-thumbnail.jpg',
				'description' => __( 'Equalizer', 'shiword' ),
				'color' => '#007cbc'
			),

			'negative' => array(
				'url' => '%s/images/backgrounds/negative.png',
				'thumbnail_url' => '%s/images/backgrounds/negative-thumbnail.jpg',
				'description' => __( 'Negative', 'shiword' ),
				'color' => '#ffc07d'
			),

			'city' => array(
				'url' => '%s/images/backgrounds/city.png',
				'thumbnail_url' => '%s/images/backgrounds/city-thumbnail.jpg',
				'description' => __( 'City', 'shiword' ),
				'color' => '#e2f1f8'
			)

		);

		$this->default_bg_images = $default_bg_images;

		foreach ( array_keys($this->default_bg_images) as $header ) {
			$this->default_bg_images[$header]['url'] =  sprintf( $this->default_bg_images[$header]['url'], get_template_directory_uri(), get_stylesheet_directory_uri() );
			$this->default_bg_images[$header]['thumbnail_url'] =  sprintf( $this->default_bg_images[$header]['thumbnail_url'], get_template_directory_uri(), get_stylesheet_directory_uri() );
		}

	}


	/**
	 * Display UI for selecting one of several default backgrounds.
	 */
	function show_default_bg_selector() {

		foreach ( $this->default_bg_images as $header_key => $header ) {

			$header_thumbnail = $header['thumbnail_url'];
			$header_url = $header['url'];
			$header_desc = $header['description'];

?>
	<div class="default-bg">

		<label>
			<input name="default-bg" type="radio" value="<?php echo esc_attr($header_key); ?>" <?php checked($header_url, get_theme_mod( 'background_image' )); ?> />
			<img src="<?php echo $header_thumbnail; ?>" alt="<?php echo esc_attr($header_desc); ?>" title="<?php echo esc_attr($header_desc); ?>" />
		</label>

		<h3><?php echo esc_attr($header_desc); ?></h3>

		<div class="default-bg-info">
			<?php echo __( 'Color', 'shiword' ); ?>: <span id="default-bg-info-col-<?php echo esc_attr($header_key); ?>"><?php echo $header['color']; ?></span>
			<input id="default-bg-info-url-<?php echo esc_attr($header_key); ?>" type="hidden" value="<?php echo $header_url; ?>">
		</div>

	</div>
<?php

		}

	}


	/**
	 * Display the custom background page.
	 */
	function admin_page() {

		$this->process_default_bg_images();

?>
	<div class="wrap" id="custom-background">

		<div class="icon32" id="sw-icon"><br></div>

		<h2><?php _e('Custom Background', 'shiword'); ?></h2>

		<?php if ( !empty($this->updated) ) { ?>
			<div id="message" class="updated">
				<p><?php printf( __( 'Background updated. <a href="%s">Visit your site</a> to see how it looks.', 'shiword' ), home_url( '/' ) ); ?></p>
			</div>
		<?php } ?>

		<table class="form-table">

			<tbody>

				<tr valign="top">

					<th scope="row"><?php _e('Preview', 'shiword'); ?></th>

					<td>
						<?php
							$background_styles = ' background-repeat: repeat-x;' . ' background-position: left bottom;';

							if ( $bgcolor = get_background_color() )
								$background_styles .= ' background-color: #' . $bgcolor . ';';

							if ( get_background_image() )
								$background_styles .= ' background-image: url(\'' . get_background_image() . '\');';
						?>
						<div id="custom-background-image" style="<?php echo $background_styles; ?>"><?php // must be double quote, see above ?>
						<?php if ( get_background_image() ) { ?>
						<img class="custom-background-image" src="<?php echo get_background_image(); ?>" style="visibility:hidden;" alt="" /><br />
						<img class="custom-background-image" src="<?php echo get_background_image(); ?>" style="visibility:hidden;" alt="" />
						<?php } ?>
						</div>
					</td>

				</tr>

				<?php if ( get_background_image() ) : ?>
				<tr valign="top">

					<th scope="row"><?php _e('Restore Original Image', 'shiword'); ?></th>

					<td>
						<form method="post" action="">
						<?php wp_nonce_field('custom-background-remove', '_wpnonce-custom-background-remove'); ?>
						<?php submit_button( __( 'Restore Original Image', 'shiword' ), 'button', 'remove-background', false ); ?><br>
						<?php _e('This will restore the original background image.', 'shiword') ?>
						</form>
					</td>

				</tr>
				<?php endif; ?>

			</tbody>

		</table>


		<form method="post" action="">

			<table class="form-table">

				<tbody>

					<tr>

						<th scope="row"><?php _e( 'Predefined themes', 'shiword' ); ?></th>

						<td>
							<div id="available-bg">
								<?php $this->show_default_bg_selector(); ?>
								<div class="clear"></div>
							</div>
						</td>

					</tr>

				</tbody>

			</table>

			<table class="form-table">

				<tbody>

					<tr valign="top">

						<th scope="row"><?php _e( 'Background Color', 'shiword' ); ?></th>

						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Background Color', 'shiword' ); ?></span></legend>
								<input type="text" name="background-color" id="background-color" value="#<?php echo esc_attr( get_background_color() ); ?>" />
							</fieldset>
						</td>
					</tr>

				</tbody>

			</table>

			<?php wp_nonce_field('custom-background'); ?>
			<?php submit_button( null, 'primary', 'save-background-options' ); ?>
		</form>

	</div>
<?php

	}

}

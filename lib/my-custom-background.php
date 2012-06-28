<?php
/**
 * The custom background stuff
 *
 * @package Shiword
 * @since Shiword 3.0
 *
 * Shiword_Custom_Background class based on WP wp-admin/custom-background.php
 *
 */

add_action( 'after_setup_theme', 'shiword_custom_background_init' );

// set up custom colors and header image
if ( !function_exists( 'shiword_custom_background_init' ) ) {
	function shiword_custom_background_init() {
		global $shiword_opt;

		if ( isset( $shiword_opt['shiword_custom_bg'] ) && $shiword_opt['shiword_custom_bg'] == 1 ) {
			// the enhanced 'custom background' support
			shiword_add_custom_background( 'shiword_custom_bg_plus' , 'shiword_admin_custom_bg_style' , '' );
		} else {
			// the standard 'custom background' support
			$args = array(
				'default-color'          => '',
				'default-image'          => '',
				'wp-head-callback'       => 'shiword_custom_bg',
				'admin-head-callback'    => '',
				'admin-preview-callback' => ''
			);
			if ( function_exists( 'get_custom_header' ) ) {
				add_theme_support( 'custom-background', $args );
			} else {
				// Compat: Versions of WordPress prior to 3.4.
				define( 'BACKGROUND_COLOR', $args['default-color'] );
				add_custom_background( $args['wp-head-callback'] , '' , '' );
			}
		}

	}
}

//Add callbacks for background image display. based on WP theme.php -> add_custom_background()
if ( !function_exists( 'shiword_add_custom_background' ) ) {
	function shiword_add_custom_background( $header_callback = '', $admin_header_callback = '', $admin_image_div_callback = '' ) {
		if ( isset( $GLOBALS['custom_background'] ) )
			return;

		if ( empty( $header_callback ) )
			$header_callback = '_custom_background_cb';

		add_action( 'wp_head', $header_callback );

		if ( ! is_admin() )
			return;

		$GLOBALS['custom_background'] =& new Shiword_Custom_Background( $admin_header_callback, $admin_image_div_callback );
		add_action( 'admin_menu', array( &$GLOBALS['custom_background'], 'init' ) );
	}
}

// custom background style (enhanced) - gets included in the site header
if ( !function_exists( 'shiword_custom_bg_plus' ) ) {
	function shiword_custom_bg_plus() {
		global $shiword_is_printpreview, $shiword_is_mobile_browser;
		if ( $shiword_is_printpreview || $shiword_is_mobile_browser ) return;

		$background = get_background_image();
		$color = get_background_color();
		if ( ! $background && ! $color ) return;
	
		$style = $color ? "background-color: #$color;" : '';
	
		if ( $background ) {
			$style .= " background-image: url('$background');";
		}
		?>
		<style type="text/css"> 
			body { <?php echo trim( $style ); ?> }
			#fixedfoot_cont { <?php echo trim( $style ); ?> }
			#head_cont { background-color: #<?php echo trim( $color ); ?>; }
		</style>
		<?php
	}
}

// custom background style - gets included in the site header
if ( !function_exists( 'shiword_custom_bg' ) ) {
	function shiword_custom_bg() {
		global $shiword_is_printpreview, $shiword_is_mobile_browser;
		if ( $shiword_is_printpreview || $shiword_is_mobile_browser ) return;

		$color = get_background_color();
		if ( ! $color ) return;
		?>
		<style type="text/css"> 
			body { background-color: #<?php echo trim( $color ); ?>; }
			#head_cont { background-color: #<?php echo trim( $color ); ?>; }
		</style>
		<?php
	}
}

class Shiword_Custom_Background {


	/* Holds default background images. */
	var $default_bg_images = array();

	/**
	 * Callback for administration header.
	 *
	 * @var callback
	 * @access private
	 */
	var $admin_header_callback;

	/**
	 * Callback for header div.
	 *
	 * @var callback
	 * @access private
	 */
	var $admin_image_div_callback;

	/**
	 * Holds the page menu hook.
	 *
	 * @var string
	 * @access private
	 */
	var $page = '';

	/**
	 * PHP4 Constructor - Register administration header callback.
	 *
	 * @param callback $admin_header_callback
	 * @param callback $admin_image_div_callback Optional custom image div output callback.
	 * @return Custom_Background
	 */
	function Custom_Background($admin_header_callback = '', $admin_image_div_callback = '') {
		$this->admin_header_callback = $admin_header_callback;
		$this->admin_image_div_callback = $admin_image_div_callback;
	}

	/**
	 * Set up the hooks for the Custom Background admin page.
	 *
	 */
	function init() {
		if ( ! current_user_can('edit_theme_options') )
			return;

		$this->page = $page = add_theme_page(__('Background', 'shiword'), __('Background', 'shiword'), 'edit_theme_options', 'custom-background', array(&$this, 'admin_page'));

		add_action("load-$page", array(&$this, 'admin_load'));
		add_action("load-$page", array(&$this, 'take_action'), 49);

		if ( $this->admin_header_callback )
			add_action("admin_head-$page", $this->admin_header_callback, 51);
	}

	/**
	 * Set up the enqueue for the CSS & JavaScript files.
	 *
	 */
	function admin_load() {
		global $shiword_version;
		wp_enqueue_script( 'sw-custom-bg-script', get_template_directory_uri() . '/js/admin-custom_background.dev.js', array( 'jquery', 'farbtastic' ), $shiword_version, true  );
		wp_enqueue_style( 'sw-custom-bg-style', get_template_directory_uri() . '/css/admin-custom_background.css', array( 'farbtastic' ), false, 'screen' );
	}

	/**
	 * Execute custom background modification.
	 *
	 */
	function take_action() {

		if ( empty($_POST) )
			return;

		if ( isset($_POST['remove-background']) ) {
			check_admin_referer('custom-background-remove', '_wpnonce-custom-background-remove');
			set_theme_mod('background_image', '');
			set_theme_mod('background_image_thumb', '');
			$this->updated = true;
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
		
		if ( isset($_POST['default-bg']) ) {
			check_admin_referer('custom-background');
			$this->process_default_bg_images();
			if ( in_array($_POST['default-bg'], array('bamboo','flowers','equalizer','negative','city')) ) {
				set_theme_mod('background_image', esc_url($this->default_bg_images[$_POST['default-bg']]['url']));
				set_theme_mod('background_image_thumb', esc_url($this->default_bg_images[$_POST['default-bg']]['thumbnail_url']) );
			}
		}

		$this->updated = true;
	}

	
	/* Process the default headers */
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
	 *
	 */
	function show_default_bg_selector() {
		foreach ( $this->default_bg_images as $header_key => $header ) {
			$header_thumbnail = $header['thumbnail_url'];
			$header_url = $header['url'];
			$header_desc = $header['description'];
			?>
			<div class="default-bg">
				<label><input name="default-bg" type="radio" value="<?php echo esc_attr($header_key); ?>" <?php checked($header_url, get_theme_mod( 'background_image' )); ?>/>
				<img src="<?php echo $header_thumbnail; ?>" alt="<?php echo esc_attr($header_desc); ?>" title="<?php echo esc_attr($header_desc); ?>" /></label>
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
	 *
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
<?php }

	if ( $this->admin_image_div_callback ) {
		call_user_func($this->admin_image_div_callback);
	} else {
?>
<h3><?php _e('Background Image', 'shiword'); ?></h3>
<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row"><?php _e('Preview', 'shiword'); ?></th>
<td>
<?php
$background_styles = ' background-repeat: repeat-x;' . ' background-position: left bottom;';
if ( $bgcolor = get_background_color() )
	$background_styles .= ' background-color: #' . $bgcolor . ';';

if ( get_background_image() ) {
	// background-image URL must be single quote, see below
	$background_styles .= ' background-image: url(\'' . get_background_image() . '\');';
}
?>
<div id="custom-background-image" style="<?php echo $background_styles; ?>"><?php // must be double quote, see above ?>
<?php if ( get_background_image() ) { ?>
<img class="custom-background-image" src="<?php echo get_background_image(); ?>" style="visibility:hidden;" alt="" /><br />
<img class="custom-background-image" src="<?php echo get_background_image(); ?>" style="visibility:hidden;" alt="" />
<?php } ?>
</div>
<?php } ?>
</td>
</tr>
<?php if ( get_background_image() ) : ?>
<tr valign="top" style="display: table-row;">
<th scope="row"><?php _e('Restore Original Image', 'shiword'); ?></th>
<td>
<form method="post" action="">
<?php wp_nonce_field('custom-background-remove', '_wpnonce-custom-background-remove'); ?>
<?php submit_button( __( 'Restore Original Image', 'shiword' ), 'button', 'remove-background', false ); ?><br/>
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
	<th scope="row"><?php _e('Predefined themes', 'shiword'); ?></th>
	<td>
		<div id="available-bg">
			<?php
				$this->show_default_bg_selector();
			?>
			<div class="clear"></div>
		</div>
	</td>
</tr>
</tbody>
</table>
<h3><?php _e('Display Options', 'shiword') ?></h3>
<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row"><?php _e( 'Color', 'shiword' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Color', 'shiword' ); ?></span></legend>
<?php $show_clear = get_background_color() ? '' : ' style="display:none"'; ?>
<input type="text" name="background-color" id="background-color" value="#<?php echo esc_attr(get_background_color()) ?>" />
<a class="hide-if-no-js" href="#" id="pickcolor"><?php _e('Select a Color', 'shiword'); ?></a> <span <?php echo $show_clear; ?>class="hide-if-no-js" id="clearcolor"> (<a href="#"><?php _e( 'Clear', 'shiword' ); ?></a>)</span>
<div class="shi_cp" id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
</fieldset></td>
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
?>

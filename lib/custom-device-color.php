<?php
/**
 * custom-device-color.php
 *
 * The custom colors page
 * Based on WP wp-admin/custom-header.php
 *
 * @package Shiword
 * @since 3.0
 */

add_action( 'after_setup_theme', 'shiword_custom_device_colors_init' );

//load custom colors
$shiword_colors = shiword_get_colors();

// set up custom colors and header image
if ( !function_exists( 'shiword_custom_device_colors_init' ) ) {
	function shiword_custom_device_colors_init() {

		shiword_register_default_device_images( array(
			'green' => array(
				'url' => '%s/images/device/white.png',
				'description' => 'white'
			),
			'black' => array(
				'url' => '%s/images/device/black.png',
				'description' => 'black'
			),
			'pink' => array(
				'url' => '%s/images/device/pink.png',
				'description' => 'pink'
			),
			'blue' => array(
				'url' => '%s/images/device/blue.png',
				'description' => 'blue'
			),
			'vector' => array(
				'url' => '%s/images/device/vector.png',
				'description' => 'vector'
			),
			'ice' => array(
				'url' => '%s/images/device/ice.png',
				'description' => 'ice'
			),
			'metal' => array(
				'url' => '%s/images/device/metal.png',
				'description' => 'metal'
			),
			'stripe' => array(
				'url' => '%s/images/device/stripe.png',
				'description' => 'stripe'
			),
			'flower' => array(
				'url' => '%s/images/device/flower.png',
				'description' => 'flower'
			),
			'wood' => array(
				'url' => '%s/images/device/wood.jpg',
				'description' => 'wood'
			)
		) );

		shiword_add_custom_device_colors();
	}
}
function shiword_get_default_colors($type) {
	// Holds default outside colors
	$shiword_default_device_bg = array(
		'device_image' => '',
		'device_color' => '#000000',
		'device_opacity' => '100',
		'device_textcolor' => '#ffffff',
		'device_button_style' => 'light'
	);
	// Holds default inside colors
	$shiword_default_device_colors = array(
		'main3' => '#21759b',
		'main4' => '#ff8d39',
		'menu1' => '#21759b',
		'menu2' => '#cccccc',
		'menu3' => '#262626',
		'menu4' => '#ffffff',
		'menu5' => '#ff8d39',
		'menu6' => '#cccccc',
	);
	if ( $type == 'out') { return $shiword_default_device_bg; }
	elseif ( $type == 'in') { return $shiword_default_device_colors; }
	else { return array_merge( $shiword_default_device_bg , $shiword_default_device_colors ); }
}

//get the theme color values. uses default values if options are empty or unset
function shiword_get_colors() {

	/* Holds default colors. */
	$default_device_colors = shiword_get_default_colors('all');

	$shiword_colors = get_option( 'shiword_colors' );
	foreach ( $default_device_colors as $key => $val ) {
		if ( ( !isset( $shiword_colors[$key] ) ) || empty( $shiword_colors[$key] ) ) {
			$shiword_colors[$key] = $default_device_colors[$key];
		}
	}
	return $shiword_colors;
}

// Register a selection of default images to be displayed as device backgrounds by the custom device color admin UI. based on WP theme.php -> register_default_headers()
if ( !function_exists( 'shiword_register_default_device_images' ) ) {
	function shiword_register_default_device_images( $headers ) {
		global $shiword_default_device_images;

		$shiword_default_device_images = array_merge( (array) $shiword_default_device_images , (array) $headers );
	}
}

// Add callbacks for device color display. based on WP theme.php -> add_custom_image_header()
if ( !function_exists( 'shiword_add_custom_device_colors' ) ) {
	function shiword_add_custom_device_colors() {
		if ( ! is_admin() )
			return;

		$GLOBALS['custom_device_color'] =& new Custom_device_color();
		add_action( 'admin_menu' , array(&$GLOBALS['custom_device_color'] , 'init' ));
	}
}

class Custom_device_color {

	/* Holds default outside colors. */
	var $default_device_bg = array();

	/* Holds default headers. */
	var $default_device_images = array();

	/* Holds default inside colors. */
	var $default_device_colors = array();
	
	/* Holds the page menu hook. */
	var $page = '';

	/* PHP4 Constructor - Register administration header callback. */
	function Custom_device_color() {
		//nothing to do here
	}

	//initialize the page
	function init() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			return;
		$this->default_device_bg = shiword_get_default_colors('out');
		$this->default_device_colors = shiword_get_default_colors('in');
		$this->page = $page = add_theme_page(__( 'Custom Colors', 'shiword' ), __( 'Custom Colors', 'shiword' ), 'edit_theme_options' , 'device-color' , array( &$this , 'admin_page' ));
		add_action( "admin_print_scripts-$page" , array(&$this, 'js_includes' ) );
		add_action( "admin_print_styles-$page" , array(&$this, 'css_includes' ) );
		add_action( "admin_head-$page" , array(&$this, 'build_style' ) );
		add_action( "admin_head-$page" , array(&$this, 'take_action' ), 50 );
	}
		
	/* Get the current step. */
	function step() {
		if ( ! isset( $_GET['step'] ) )
			return 1;

		$step = (int) $_GET['step'];
		if ( $step < 1 || 3 < $step )
			$step = 1;

		return $step;
	}

	/* Set up the enqueue for the JavaScript files. */
	function js_includes() {

		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'shiword-custom-colors-script', get_template_directory_uri() . '/js/admin-custom_colors.dev.js', array( 'jquery','jquery-ui-slider','jquery-ui-draggable'), shiword_get_info( 'version' ), true ); //shiword js

	}

	/* Set up the enqueue for the CSS files */
	function css_includes() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'shiword-custom-colors-style', get_template_directory_uri() . '/css/admin-custom_colors.css', array(), false, 'screen' );
	}

	/* Options check. */
	function take_action() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			return;
		
		$colors = get_option( 'shiword_colors' );
		//if options are empty, sets the default values
		if ( empty( $colors ) ) {
			$colors = array_merge( $this->default_device_colors , $this->default_device_bg );
			update_option( 'shiword_colors' , $colors );
		}
	}

	/* Process the default headers */
	function process_default_device_images() {
		global $shiword_default_device_images;

		if ( !empty($this->headers) )
			return;

		if ( !isset($shiword_default_device_images) )
			return;

		$this->default_device_images = $shiword_default_device_images;
		foreach ( array_keys($this->default_device_images) as $header ) {
			$this->default_device_images[$header]['url'] =  sprintf( $this->default_device_images[$header]['url'], get_template_directory_uri(), get_stylesheet_directory_uri() );
		}
	}

	/* Display UI for selecting one of several default headers. */
	function show_default_header_selector() {
		global $shiword_colors;
		echo '<div id="available-headers">';
		foreach ( $this->default_device_images as $header_key => $header ) {
			$header_url = $header['url'];
			$header_desc = $header['description'];
			echo '<label class="default-device-image">';
			echo '<input class="default-device-input" name="default-device-image" type="radio" value="' . esc_attr($header_key) . '" ' . checked($header_url, $shiword_colors['device_image'], false) . ' />';
			echo '<img src="' . $header_url . '" alt="' . esc_attr($header_desc) .'" title="' . esc_attr($header_desc) .'" />';
			echo '</label>';
		}
		echo '<div class="clear"></div></div>';
	}

	function build_style() {
		global $shiword_colors;

		$color = get_background_color();
		if ( ! $color ) $color = 'b6b6b6';

?>
	<style type="text/css">#headimg-bg { background-color: #<?php echo $color; ?> !important; }</style>
<?php

		$device_rgba = shiword_hex2rgba( $shiword_colors['device_color'], $shiword_colors['device_opacity']);

?>
	<style type="text/css">
		#headimage {
			background: <?php echo $device_rgba; ?> url('<?php echo $shiword_colors['device_image']; ?>') left top repeat;
		}
		#headimg_overlay a, #desc {
			color:<?php echo $shiword_colors['device_textcolor']; ?>;
		}
		#preview-body .preview-link, #preview-footer .preview-link, #preview-meta .preview-link {
			color:<?php echo $shiword_colors['main3']; ?>;
		}
		#preview-body .preview-linkhi, #preview-footer .preview-linkhi, #preview-meta .preview-linkhi {
			color:<?php echo $shiword_colors['main4']; ?>;
		}
		#preview-pages, #preview-menu {
			background-color:<?php echo $shiword_colors['menu1']; ?>;
			border-color:<?php echo $shiword_colors['menu2']; ?>;
		}
		#preview-menu span {
			border-color:<?php echo $shiword_colors['menu6']; ?>;
		}
		#preview-pages .preview-text, #preview-menu .preview-text {
			color:<?php echo $shiword_colors['menu3']; ?>;
		}
		#preview-pages .preview-link, #preview-menu .preview-link {
			color:<?php echo $shiword_colors['menu4']; ?>;
		}
		#preview-pages .preview-linkhi, #preview-menu .preview-linkhi {
			color:<?php echo $shiword_colors['menu5']; ?>;
		}
	</style>
	<!-- InternetExplorer really sucks! -->
	<!--[if lte IE 8]>
	<style type="text/css">
		#headimage {
			background: <?php echo $shiword_colors['device_color']; ?> url('<?php echo $shiword_colors['device_image']; ?>') left top repeat;
		}
	</style>
	<![endif]-->
<?php

	}

	// display the preview div
	function show_preview() {
			global $shiword_colors;
			$device_rgba = shiword_hex2rgba( $shiword_colors['device_color'], $shiword_colors['device_opacity']);

?>
	<div id="headimg-bg">
		<div id="headimage">
			<div id="headimg_overlay">
				<h1><a id="name"><?php bloginfo( 'name' ); ?></a></h1>
				<div id="desc"><?php bloginfo( 'description' ); ?></div>
				
			
				<div id="preview-main">
					<div id="preview-button" class="sw-variant-<?php echo $shiword_colors['device_button_style']; ?>"></div>
					<div id="preview-navi" class="sw-variant-<?php echo $shiword_colors['device_button_style']; ?>"></div>
					<div id="preview-pages">
						<span class="preview-text"><?php _e( 'Text', 'shiword' ); ?> </span><span class="preview-link"><?php _e( 'Links', 'shiword' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
					</div>
					<div id="preview-meta">
						<span class="preview-text"><?php _e( 'Text', 'shiword' ); ?> </span><span class="preview-link"><?php _e( 'Links', 'shiword' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
					</div>
					<div id="preview-body">
						<span class="preview-text"><?php _e( 'Text', 'shiword' ); ?> </span><span class="preview-link"><?php _e( 'Links', 'shiword' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
					</div>
					<div id="preview-footer">
						<span class="preview-text"><?php _e( 'Text', 'shiword' ); ?> </span><span class="preview-link"><?php _e( 'Links', 'shiword' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
					</div>
					<div id="preview-menu">
						<span class="preview-text"><?php _e( 'Text', 'shiword' ); ?> </span><span class="preview-link"><?php _e( 'Links', 'shiword' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
					</div>
				</div>
				
				
				
			</div>
		</div>
	</div>
<?php

	}
	
	/* Display main custom colors page. */
	function step_1() {
		
		global $shiword_colors;
		
		/* Holds the inside colors descriptions */
		$default_device_colors_descr = array(
			'menu1' => __('Background', 'shiword' ),
			'menu2' => __('Borders', 'shiword' ),
			'menu3' => __('Text', 'shiword' ),
			'menu4' => __('Links', 'shiword' ),
			'menu5' => __('Highlighted Links', 'shiword' ),
			'menu6' => __('Inner borders', 'shiword' ),
			'main3' => __('Links', 'shiword' ),
			'main4' => __('Highlighted Links', 'shiword' ),
		);

		$this->process_default_device_images();

?>
<div class="wrap">
	<div class="icon32" id="sw-icon"><br></div>
	<h2><?php echo shiword_get_info( 'current_theme' ) . ' - ' . __( 'Custom Colors', 'shiword' ); ?></h2>

	<?php if ( ! empty( $this->updated ) ) { ?>
		<div id="message" class="updated">
			<p><?php printf( __( 'Colors updated. <a href="%s">Visit your site</a> to see how it looks.' , 'shiword' ) , home_url( '/' ) ); ?></p>
		</div>
	<?php } ?>

	<?php $this->show_preview(); ?>

	<div class="fields_wrap">
		<h3 class="h3_field"><?php _e( 'Exterior Colors' , 'shiword' ) ?> <a class="hide-if-no-js" href="#" onclick="shiwordCustomColors.secOpen('.shi_bgc'); return false;">&raquo;</a></h3>
		<div class="shi_bgc">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<td style="width:200px;"><?php _e( 'Upload Image', 'shiword' ); ?></td>
						<td>
							<?php _e( 'You can upload a custom skin image.' , 'shiword' ); ?>
							<br />
							<form enctype="multipart/form-data" id="upload-form" method="post" action="<?php echo esc_attr( add_query_arg( 'step' , 2 ) ) ?>">
								<p>
									<label for="upload"><?php _e( 'Choose an image from your computer:', 'shiword' ); ?></label><br />
									<input type="file" id="upload" name="import" />
									<input type="hidden" name="action" value="save" />
									<?php wp_nonce_field( 'load-custom-image' , 'nonce-load-custom-image' ) ?>
									<input type="submit" class="button" value="<?php esc_attr_e( 'Upload', 'shiword' ); ?>" />
								</p>
							</form>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<form method="post" action="<?php echo esc_attr( add_query_arg( 'step' , 3 ) ) ?>">
			<div class="shi_bgc">
				<table class="form-table">
					<tbody>
						<tr>
							<td><?php _e( 'Choose Image', 'shiword' ); ?></td>
							<td>
								<p>
									<label for="choose-from-library-link"><?php _e( 'Choose an image from your media library:', 'shiword' ); ?></label><br />
									<input type="text" id="custom-device-image" name="custom-device-image" />
									<a id="choose-skin-from-library-link" class="button"
										data-choose="<?php esc_attr_e( 'Choose a Custom Skin', 'shiword' ); ?>"
										data-update="<?php esc_attr_e( 'Set as skin', 'shiword' ); ?>"><?php _e( 'Choose Image', 'shiword' ); ?></a>
								</p>
							</td>
						</tr>
						<?php if ( ! empty( $this->default_device_images ) ) { ?>
							<tr valign="top">
								<td style="width:200px;"><?php _e( 'Default Images', 'shiword' ); ?></td>
								<td>
									<?php _e( 'If you don&lsquo;t want to upload your own image, you can choose from one of the following.' , 'shiword' ) ?>
									<?php $this->show_default_header_selector(); ?>
								</td>
							</tr>
						<?php }

						if ( $shiword_colors['device_image'] != '' ) { ?>
							<tr valign="top">
								<td style="width:200px;"><?php _e( 'Remove Image', 'shiword' ); ?></td>
								<td>
									<?php _e( 'This will remove the background image.' , 'shiword' ) ?>
									<input type="submit" class="button" name="removedeviceimage" value="<?php esc_attr_e( 'Remove Image', 'shiword' ); ?>" />
								</td>
							</tr>
						<?php } ?>
						<tr valign="top">
							<td style="width:200px;"><?php _e( 'Background Color', 'shiword' ) ?></td>
							<td>
								<input class="color_picker" type="text" name="devicecolor" id="shi_input_1" value="<?php echo $shiword_colors['device_color']; ?>" data-default-color="<?php echo $this->default_device_bg['device_color']; ?>" />
								<span class="description hide-if-js"><?php _e( 'Default' , 'shiword' ); ?>: <?php echo $this->default_device_bg['device_color']; ?></span>
								<span class="hide-if-no-js"><a href="#" onclick="shiwordCustomColors.pickColor('shi_input_1','transparent'); return false;"><?php _e( "Set to \"transparent\"" , 'shiword' ); ?></a></span>
								<br />
								<div id="alpha_slider_wrap" style="padding-top:5px;">
									<span class="opaopt" style="margin-left: 40px;"><?php _e( 'Opacity', 'shiword' ); ?> [0-100] <!--[if lte IE 8]><span style="color:#ff0000;">Not supported in Internet Explorer 8 and below </span><![endif]--><input type="text" name="deviceopacity" id="shi_input_1a" value="<?php echo $shiword_colors['device_opacity']; ?>" maxlength="3" size="3" /> %</span>
									<div id="alpha_slider" class="slider hide-if-no-js"></div>
								</div>
							</td>
						</tr>
						<tr valign="top">
							<td style="width:200px;"><?php _e( 'Text Color', 'shiword' ); ?></td>
							<td>
								<input class="color_picker" type="text" name="devicetextcolor" id="shi_input_2" value="<?php echo $shiword_colors['device_textcolor']; ?>" data-default-color="<?php echo $this->default_device_bg['device_textcolor']; ?>" />
								<span class="description hide-if-js"><?php _e( 'Default' , 'shiword' ); ?>: <?php echo $this->default_device_bg['device_textcolor']; ?></span>
							</td>
						</tr>
						<tr valign="top">
							<td style="width:200px;"><?php _e( 'Buttons Style' , 'shiword' ); ?></td>
							<td>
								<select id="shi_select_1" name="devicebuttonstyle">
									<option value="light" <?php selected( $shiword_colors['device_button_style'], 'light' ); ?>><?php _e( 'light', 'shiword' ); ?></option>
									<option value="dark" <?php selected( $shiword_colors['device_button_style'], 'dark' ); ?>><?php _e( 'dark', 'shiword' ); ?></option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		
			<div style="position: relative">
				<h3 class="h3_field"><?php _e( 'Interior Colors' , 'shiword' ) ?> <a class="hide-if-no-js" href="#" onclick="shiwordCustomColors.secOpen('.shi_cc'); return false;">&raquo;</a></h3>
				<div class="shi_cc">
					<table class="form-table">
						<?php foreach ( $this->default_device_colors as $key => $val ) { ?>
							<?php 
								if ( $key == 'main3' ) echo '<tr><td style="font-weight:bold;" colspan="2">' . __( 'Main Content', 'shiword' ) . '</td></tr>';
								elseif ( $key == 'menu1' ) echo '<tr><td style="font-weight:bold; border-top:1px solid #CCCCCC;" colspan="2">' . __( 'Pages Menu and Floating Menu', 'shiword' ) . '</td></tr>';
							?>
							<tr>
								<td style="width:200px;"><?php echo $default_device_colors_descr[$key]; ?></td>
								<td>
									<input class="color_picker" type="text" name="<?php echo $key; ?>" id="shi_input_<?php echo $key; ?>" value="<?php echo $shiword_colors[$key]; ?>" data-default-color="<?php echo $val; ?>" />
									<span class="description hide-if-js"><?php _e( 'Default' , 'shiword' ); ?>: <?php echo $val; ?></span>
								</td>
							</tr>
						<?php }	?>
					</table>
				</div>
			</div>

			
			<?php
			wp_nonce_field( 'set-custom-colors' , 'nonce-set-custom-colors' ); ?>
			<p class="submit"><input type="submit" class="button-primary" name="save-device-options" value="<?php esc_attr_e( 'Save Changes', 'shiword' ); ?>" /></p>
		</form>
	</div>
</div>
<?php

	}

	/* Run second step of custom colors page - upload custom image. */
	function step_2() {
		global $shiword_colors;
		
		// exit if empty file list
		if( empty ($_FILES['import']['name']) ) return $this->step_1();
		
		check_admin_referer( 'load-custom-image' , 'nonce-load-custom-image' );
		$overrides = array( 'test_form' => false );
		$file = wp_handle_upload( $_FILES['import'] , $overrides );

		if ( isset($file['error']) )
			wp_die( $file['error'],  __( 'Image Upload Error', 'shiword' ) );

		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$filename = basename($file);

		// Construct the object array
		$object = array(
		'post_title' => $filename,
		'post_content' => $url,
		'post_mime_type' => $type,
		'guid' => $url);

		// Save the data
		$id = wp_insert_attachment( $object , $file );

		// Add the meta-data
		wp_update_attachment_metadata( $id , wp_generate_attachment_metadata( $id , $file ) );

		$shiword_colors['device_image'] = esc_url($url);
		update_option( 'shiword_colors' , $shiword_colors );
		do_action( 'wp_create_file_in_uploads' , $file , $id ); // For replication
		return $this->finished();
	}
	
	/* Run third step of custom colors page - updates color options. */
	function step_3() {
		check_admin_referer( 'set-custom-colors' , 'nonce-set-custom-colors' );
		if ( ! current_user_can( 'edit_theme_options' ) )
			return $this->step_1();
					
		$shiword_colors = get_option( 'shiword_colors' );
		//if options are empty, sets the default values
		if ( empty( $shiword_colors ) ) {
			$shiword_colors = array_merge( $this->default_device_colors , $this->default_device_bg );
			update_option( 'shiword_colors' , $shiword_colors );
		}

		if ( empty( $_POST ) )
			return $this->step_1();
		
		if ( isset( $_POST['removedeviceimage'] ) ) {
			$shiword_colors['device_image'] = '' ;
			update_option( 'shiword_colors' , $shiword_colors );
			return $this->finished();
		}

		$shiword_colors['device_textcolor'] = $this->default_device_bg['device_textcolor'];
		if ( isset( $_POST['devicetextcolor'] ) ) {
			$_POST['devicetextcolor'] = str_replace( '#' , '' , $_POST['devicetextcolor'] );
			if ( 'transparent' == $_POST['devicetextcolor'] ) {
				$shiword_colors['device_textcolor'] = 'transparent';
			} else {
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $_POST['devicetextcolor'] );
				if ( strlen($color) == 6 || strlen($color) == 3 )
					$shiword_colors['device_textcolor'] = '#' . $color;
			}
		}
		$shiword_colors['device_color'] = $this->default_device_bg['device_color'];
		if ( isset( $_POST['devicecolor'] ) ) {
			$_POST['devicecolor'] = str_replace( '#' , '' , $_POST['devicecolor'] );
			if ( 'transparent' == $_POST['devicecolor'] ) {
				$shiword_colors['device_color'] = 'transparent';
			} else {
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $_POST['devicecolor'] );
				if ( strlen($color) == 6 || strlen($color) == 3 )
					$shiword_colors['device_color'] = '#' . $color;
			}
		}
		$shiword_colors['device_opacity'] = $this->default_device_bg['device_opacity'];
		if ( isset( $_POST['deviceopacity'] ) ) {
			$alpha = intval( $_POST['deviceopacity'] );
			if ( ( $alpha < 0 ) || ( $alpha > 100 )) $alpha = 100;
			$shiword_colors['device_opacity'] = strval( $alpha );
		}
		$shiword_colors['device_button_style'] = $this->default_device_bg['device_button_style'];
		if ( isset( $_POST['devicebuttonstyle'] ) ) {
			$shiword_colors['device_button_style'] = ( in_array( $_POST['devicebuttonstyle'], array( 'light', 'dark' ) ) ) ? $_POST['devicebuttonstyle'] : 'light';
		}
		if ( isset($_POST['default-device-image']) ) {
			$this->process_default_device_images();
			if ( isset($this->default_device_images[$_POST['default-device-image']]) )
				$shiword_colors['device_image'] = esc_url( $this->default_device_images[$_POST['default-device-image']]['url'] );
		}
		if ( isset($_POST['custom-device-image']) ) {
			$shiword_colors['device_image'] = esc_url( $_POST['custom-device-image'] );
		}
		foreach ( $this->default_device_colors as $key => $val ) {
		
			if ( isset( $_POST[$key] ) ) {
				$_POST[$key] = str_replace( '#' , '' , $_POST[$key] );
				if ( 'transparent' == $_POST[$key] ) {
					$shiword_colors[$key] = 'transparent';
				} else {
					$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $_POST[$key] );
					if ( strlen($color) == 6 || strlen($color) == 3 ) {
						$shiword_colors[$key] = '#' . $color ;
					} else {
						$shiword_colors[$key] = $val ;
					}
				}
			} else {
				$shiword_colors[$key] = $val ;
			}
		}
		update_option( 'shiword_colors' , $shiword_colors );
		return $this->finished();
	}

	/* Display last step of custom header image page. */
	function finished() {
		global $shiword_colors;
		$shiword_colors = shiword_get_colors();
		$this->updated = true;
		$this->init();
		$this->step_1();
	}

	/* Display the page based on the current step. */
	function admin_page() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			wp_die( __( 'You do not have permission to customize headers.', 'shiword' ) );
		$step = $this->step();
		if ( 1 == $step )
			$this->step_1();
		elseif ( 2 == $step )
			$this->step_2();
		elseif ( 3 == $step )
			$this->step_3();
	}

}
?>

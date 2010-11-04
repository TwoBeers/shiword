<?php
/* 
The custom device color script. 

Based on WP wp-admin/custom-header.php

*/

class Custom_device_color {

	/* Callback for administration header. */
	var $admin_header_callback;

	/* Callback for header div. */
	var $admin_image_div_callback;

	/* Holds default headers. */
	var $default_device_colors = array();

	/* Holds the page menu hook. */
	var $page = '';

	/* PHP4 Constructor - Register administration header callback. */
	function Custom_device_color($admin_header_callback, $admin_image_div_callback = '' ) {
		$this->admin_header_callback = $admin_header_callback;
		$this->admin_image_div_callback = $admin_image_div_callback;
	}

	/* Set up the hooks for the Custom Header admin page. */
	function init() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			return;

		$this->page = $page = add_theme_page(__( 'Device color' , 'shiword' ), __( 'Device color' , 'shiword' ), 'edit_theme_options' , 'device-color' , array( &$this , 'admin_page' ));

		add_action( "admin_print_scripts-$page" , array(&$this, 'js_includes' ) );
		add_action( "admin_print_styles-$page" , array(&$this, 'css_includes' ) );
		add_action( "admin_head-$page" , array(&$this, 'take_action' ), 50 );
		add_action( "admin_head-$page" , array(&$this, 'js' ), 50 );
		add_action( "admin_head-$page" , $this->admin_header_callback, 51 );
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
		wp_enqueue_script( 'farbtastic' );
	}

	/* Set up the enqueue for the CSS files */
	function css_includes() {
		wp_enqueue_style( 'farbtastic' );
	}

	/* Check if header text is allowed */
	function header_text() {
		return true;
	}

	/* Execute custom header modification. */
	function take_action() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			return;

		if ( empty( $_POST ) )
			return;

		$this->updated = true;

		if ( isset( $_POST['resetdevicecolor'] ) ) {
			check_admin_referer( 'custom-device-color-options' , '_wpnonce-custom-device-color-options' );
			remove_theme_mod( 'sw_device_image' );
			set_theme_mod( 'sw_device_color' , '0a0a0a' );
			return;
		}

		if ( isset( $_POST['resetdevicetext'] ) ) {
			check_admin_referer( 'custom-device-color-options' , '_wpnonce-custom-device-color-options' );
			remove_theme_mod( 'sw_device_textcolor' );
			return;
		}

		if ( isset( $_POST['removedeviceimage'] ) ) {
			check_admin_referer( 'custom-device-color-options' , '_wpnonce-custom-device-color-options' );
			set_theme_mod( 'sw_device_image' , '' );
			return;
		}

		if ( isset( $_POST['devicetextcolor'] ) ) {
			check_admin_referer( 'custom-device-color-options' , '_wpnonce-custom-device-color-options' );
			$_POST['devicetextcolor'] = str_replace( '#' , '' , $_POST['devicetextcolor'] );
			if ( 'transparent' == $_POST['devicetextcolor'] ) {
				set_theme_mod( 'sw_device_textcolor' , 'transparent' );
			} else {
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $_POST['devicetextcolor'] );
				if ( strlen($color) == 6 || strlen($color) == 3 )
					set_theme_mod( 'sw_device_textcolor' , $color );
			}
		}
		if ( isset( $_POST['devicecolor'] ) ) {
			check_admin_referer( 'custom-device-color-options' , '_wpnonce-custom-device-color-options' );
			$_POST['devicecolor'] = str_replace( '#' , '' , $_POST['devicecolor'] );
			if ( 'transparent' == $_POST['devicecolor'] ) {
				set_theme_mod( 'sw_device_color' , 'transparent' );
			} else {
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $_POST['devicecolor'] );
				if ( strlen($color) == 6 || strlen($color) == 3 )
					set_theme_mod( 'sw_device_color' , $color );
			}
		}

		if ( isset($_POST['default-device-image']) ) {
			check_admin_referer( 'custom-device-color-options' , '_wpnonce-custom-device-color-options' );
			$this->process_default_device_colors();
			if ( isset($this->default_device_colors[$_POST['default-device-image']]) )
				set_theme_mod( 'sw_device_image' , esc_url( $this->default_device_colors[$_POST['default-device-image']]['url'] ) );
		}
	}

	/* Process the default headers */
	function process_default_device_colors() {
		global $sw_default_device_images;

		if ( !empty($this->headers) )
			return;

		if ( !isset($sw_default_device_images) )
			return;

		$this->default_device_colors = $sw_default_device_images;
		foreach ( array_keys($this->default_device_colors) as $header ) {
			$this->default_device_colors[$header]['url'] =  sprintf( $this->default_device_colors[$header]['url'], get_template_directory_uri(), get_stylesheet_directory_uri() );
		}
	}

	/* Display UI for selecting one of several default headers. */
	function show_default_header_selector() {
		echo '<div id="available-headers">';
		foreach ( $this->default_device_colors as $header_key => $header ) {
			$header_url = $header['url'];
			$header_desc = $header['description'];
			echo '<div class="default-device-image">';
			echo '<input class="default-device-input" name="default-device-image" type="radio" value="' . esc_attr($header_key) . '" ' . checked($header_url, get_theme_mod( 'sw_device_image' ), false) . ' />';
			echo '<img src="' . $header_url . '" alt="' . esc_attr($header_desc) .'" title="' . esc_attr($header_desc) .'" />';
			echo '</div>';
		}
		echo '<div class="clear"></div></div>';
	}

	/* Execute Javascript depending on step. */
	function js() {
		$this->js_1();
	}

	/* Display Javascript. */
	function js_1() { ?>
<script type="text/javascript">
/* <![CDATA[ */
	var text_objects = ['#name', '#desc', '#devicetextcolor', '#headimage'];
	var farbtastic;
	var farbtastic2;

	function pickColor(color) {
		jQuery('#name').css('color', color.replace('#transparent', 'transparent'));
		jQuery('#desc').css('color', color.replace('#transparent', 'transparent'));
		jQuery('#devicetextcolor').val(color);
		farbtastic.setColor(color);
	}
	function pickColor2(color) {
		jQuery('#headimage').css('background-color', color.replace('#transparent', 'transparent'));
		jQuery('#devicecolor').val(color);
		farbtastic2.setColor(color);
	}

	jQuery(document).ready(function() {
		jQuery('#pickdevicetextcolor').click(function() {
			jQuery('#devicetextcolor-picker').show();
		});
		jQuery('#pickdevicecolor').click(function() {
			jQuery('#devicecolor-picker').show();
		});

		jQuery('#devicetextcolor').keyup(function() {
			var _hex = jQuery('#devicetextcolor').val();
			var hex = _hex;
			if ( hex[0] != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			if ( hex != _hex )
				jQuery('#devicetextcolor').val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				pickColor( hex );
			if ( hex.length == 1 )
				pickColor( '#transparent' );
		});
		jQuery('#devicecolor').keyup(function() {
			var _hex = jQuery('#devicecolor').val();
			var hex = _hex;
			if ( hex[0] != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			if ( hex != _hex )
				jQuery('#devicecolor').val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				pickColor2( hex );
			if ( hex.length == 1 )
				pickColor2( '#transparent' );
		});

		jQuery(document).mousedown(function(){
			jQuery('.color-picker').each( function() {
				var display = jQuery(this).css('display');
				if (display == 'block')
					jQuery(this).fadeOut(2);
			});
		});

		jQuery('.default-device-input').click(function() {
			var cur_img = jQuery(this).parent().children('img').attr("src");
			jQuery('#headimage').css('background-image', 'url("' + cur_img + '")');
		});
		
		farbtastic = jQuery.farbtastic('#devicetextcolor-picker', function(color) { pickColor(color); });
		<?php if ( $color = get_theme_mod('sw_device_textcolor', SW_DEVICE_TEXTCOLOR) ) { ?>
		pickColor('#<?php echo $color; ?>');
		<?php } ?>
		farbtastic2 = jQuery.farbtastic('#devicecolor-picker', function(color) { pickColor2(color); });
		<?php if ( $color = get_theme_mod('sw_device_color', SW_DEVICE_COLOR) ) { ?>
		pickColor2('#<?php echo $color; ?>');
		<?php } ?>

		});
</script>
<?php
	}

	/* Display first step of custom header image page. */
	function step_1() {
		$this->process_default_device_colors();
?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Custom Device Colors' , 'shiword' ); ?></h2>

	<?php if ( ! empty( $this->updated ) ) { ?>
		<div id="message" class="updated">
			<p><?php printf( __( 'Device colors updated. <a href="%s">Visit your site</a> to see how it looks.' , 'shiword' ) , home_url( '/' ) ); ?></p>
		</div>
	<?php } ?>

	<h3><?php _e( 'Device Background Color' , 'shiword' ) ?></h3>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Preview' ); ?></th>
				<td >
					<?php if ( $this->admin_image_div_callback ) {
						call_user_func( $this->admin_image_div_callback );
					} else {
					?>
						<div id="headimg-bg">
							<?php
								if ( 'transparent' == get_sw_device_color() )
									$color = 'transparent';
								else
									$color = '#' . get_sw_device_color();
							?>
							<div id="headimage" style="background: <?php echo $color; ?> url(<?php esc_url ( sw_device_image() ); ?>) left top repeat;">
								<div id="headimg_overlay">
									<?php
									if ( 'blank' == get_theme_mod( 'sw_device_textcolor' , SW_DEVICE_TEXTCOLOR) || '' == get_theme_mod( 'sw_device_textcolor' , SW_DEVICE_TEXTCOLOR) )
										$style = ' style="color:transparent;"';
									else
										$style = ' style="color:#' . get_theme_mod( 'sw_device_textcolor' , SW_DEVICE_TEXTCOLOR ) . ';"';
									?>
									<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
									<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
								</div>
							</div>
						</div>
					<?php } ?>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e( 'Upload Image' ); ?></th>
				<td>
					<?php _e( 'You can upload a custom background image.' , 'shiword' ); ?>
					<br />
					<form enctype="multipart/form-data" id="upload-form" method="post" action="<?php echo esc_attr( add_query_arg( 'step' , 2 ) ) ?>">
						<p>
							<label for="upload"><?php _e( 'Choose an image from your computer:' ); ?></label><br />
							<input type="file" id="upload" name="import" />
							<input type="hidden" name="action" value="save" />
							<?php wp_nonce_field( 'custom-device-color-upload' , '_wpnonce-custom-device-color-upload' ) ?>
							<input type="submit" class="button" value="<?php esc_attr_e( 'Upload' ); ?>" />
						</p>
					</form>
				</td>
			</tr>
		</tbody>
	</table>

	<form method="post" action="<?php echo esc_attr( add_query_arg( 'step' , 1 ) ) ?>">
		<table class="form-table">
			<tbody>
				<?php if ( ! empty( $this->default_device_colors ) ) { ?>
					<tr valign="top">
						<th scope="row"><?php _e( 'Default Images' ); ?></th>
						<td>
							<?php _e( 'If you don&lsquo;t want to upload your own image, you can use one of these images.' , 'shiword' ) ?>
							<?php $this->show_default_header_selector(); ?>
						</td>
					</tr>
				<?php }

				if ( get_sw_device_image() ) { ?>
					<tr valign="top">
						<th scope="row"><?php _e( 'Remove Image' ); ?></th>
						<td>
							<?php _e( 'This will remove the background image.' , 'shiword' ) ?>
							<input type="submit" class="button" name="removedeviceimage" value="<?php esc_attr_e( 'Remove Image' , 'shiword' ); ?>" />
						</td>
					</tr>
				<?php } ?>
				<tr valign="top">
					<th scope="row"><?php _e( 'Color' ); ?></th>
					<td>
						<input type="text" name="devicecolor" id="devicecolor" value="#<?php echo esc_attr(get_background_color()) ?>" />
						<a class="hide-if-no-js showpick" href="#" id="pickdevicecolor" onclick="return false;"><?php _e( 'Select a Color' ); ?></a>
						<div class="color-picker" id="devicecolor-picker" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
						&nbsp;-&nbsp;
						<a class="hide-if-no-js" href="#" onclick="pickColor2('#transparent'); return false;"><?php _e( "set to \"transparent\"" , 'shiword' ); ?></a>

					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Reset Color' ); ?></th>
					<td>
						<?php _e( 'This will restore the original device color.' , 'shiword' ) ?>
						<input type="submit" class="button" name="resetdevicecolor" value="<?php esc_attr_e( 'Restore Color' , 'shiword' ); ?>" />
					</td>
				</tr>

			</tbody>
		</table>
		<?php if ( $this->header_text() ) { ?>
			<h3><?php _e( 'Device Text Color' ) ?></h3>
			<table class="form-table">
				<tbody>
					<tr valign="top" id="text-color-row">
						<th scope="row"><?php _e( 'Text Color' ); ?></th>
						<td>
							<input type="text" name="devicetextcolor" id="devicetextcolor" value="#<?php echo esc_attr( get_theme_mod( 'sw_device_textcolor' , SW_DEVICE_TEXTCOLOR ) ); ?>" />
							<a class="hide-if-no-js showpick" href="#" id="pickdevicetextcolor" onclick="return false;"><?php _e( 'Select a Color' ); ?></a>
							<div class="color-picker" id="devicetextcolor-picker" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
							&nbsp;-&nbsp;
							<a class="hide-if-no-js" href="#" onclick="pickColor('#transparent'); return false;"><?php _e( "set to \"transparent\"" , 'shiword' ); ?></a>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Reset Text Color' ); ?></th>
						<td>
							<?php _e( 'This will restore the original text color.' , 'shiword' ) ?>
							<input type="submit" class="button" name="resetdevicetext" value="<?php esc_attr_e( 'Restore Color' , 'shiword' ); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
		<?php }
		wp_nonce_field( 'custom-device-color-options' , '_wpnonce-custom-device-color-options' ); ?>
		<p class="submit"><input type="submit" class="button-primary" name="save-device-options" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
	</form>
</div>

<?php }

	/* Run second step of custom header image page. */
	function step_2() {
		check_admin_referer( 'custom-device-color-upload' , '_wpnonce-custom-device-color-upload' );
		$overrides = array( 'test_form' => false );
		$file = wp_handle_upload( $_FILES['import'] , $overrides );

		if ( isset($file['error']) )
			wp_die( $file['error'],  __( 'Image Upload Error' ) );

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

		set_theme_mod( 'sw_device_image' , esc_url($url));
		do_action( 'wp_create_file_in_uploads' , $file , $id ); // For replication
		return $this->finished();
	}


	/* Display last step of custom header image page. */
	function finished() {
		$this->updated = true;
		$this->step_1();
	}

	/* Display the page based on the current step. */
	function admin_page() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			wp_die(__( 'You do not have permission to customize headers.' ));
		$step = $this->step();
		if ( 1 == $step )
			$this->step_1();
		elseif ( 2 == $step )
			$this->step_2();
	}

}
?>

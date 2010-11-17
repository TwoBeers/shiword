<?php
/* 
The custom device color script. 

Based on WP wp-admin/custom-header.php

*/

class Custom_device_color {

	/* Holds default outside colors. */
	var $default_device_bg = array(
		'device_image' => '', //bg
		'device_color' => '#000', //bg
		'device_textcolor' => '#fff', //bg
	);

	/* Holds default headers. */
	var $default_device_images = array();

	/* Holds default inside colors. */
	var $default_device_colors = array(
		'main1' => '#111111',
		'main2' => '#aaaaaa',
		'main3' => '#21759b',
		'main4' => '#ff8d39',
		'main5' => '#404040',
		'menu1' => '#21759b',
		'menu2' => '#cccccc',
		'menu3' => '#262626',
		'menu4' => '#ffffff',
		'menu5' => '#ff8d39',
		'menu6' => '#cccccc',
		'meta1' => '#262626',
		'meta2' => '#cccccc',
		'meta3' => '#aaaaaa',
		'meta4' => '#21759b',
		'meta5' => '#ff8d39',
		'meta6' => '#404040'
	);
	
	var $default_device_colors_descr = array(
		'meta1' => 'background',
		'meta2' => 'borders',
		'meta3' => 'text',
		'meta4' => 'links',
		'meta5' => 'links highlighted',
		'meta6' => 'inner borders',
		'menu1' => 'background',
		'menu2' => 'borders',
		'menu3' => 'text',
		'menu4' => 'links',
		'menu5' => 'links highlighted',
		'menu6' => 'inner borders',
		'main1' => 'background',
		'main2' => 'text',
		'main3' => 'links',
		'main4' => 'links highlighted',
		'main5' => 'inner borders'
	);

	/* Holds the page menu hook. */
	var $page = '';

	/* PHP4 Constructor - Register administration header callback. */
	function Custom_device_color() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			return;

		add_action('admin_menu', array(&$this, 'admin_menu'));
	}

	function admin_menu() {
		$this->page = $page = add_theme_page(__( 'Device color' , 'shiword' ), __( 'Device color' , 'shiword' ), 'edit_theme_options' , 'device-color' , array( &$this , 'admin_page' ));
		add_action( "admin_print_scripts-$page" , array(&$this, 'js_includes' ) );
		add_action( "admin_print_styles-$page" , array(&$this, 'css_includes' ) );
		add_action( "admin_head-$page" , array(&$this, 'take_action' ), 50 );
		add_action( "admin_head-$page" , array(&$this, 'js' ), 50 );
		register_setting( 'shiw_colors_group', 'shiword_colors' );
	}
		
	/* Get the current step. */
	function step() {
		if ( ! isset( $_GET['step'] ) )
			return 1;

		$step = (int) $_GET['step'];
		if ( $step < 1 || 2 < $step )
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
		wp_enqueue_style( 'shi_cdcpanel_style', get_bloginfo( 'stylesheet_directory' ) . '/css/custom-device-color.css', false, '', 'screen' );
	}

	/* Check if header text is allowed */
	function header_text() {
		return true;
	}

	/* Execute custom header modification. */
	function take_action() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			return;
			
		$shiword_colors = get_option( 'shiword_colors' );
		//if options are empty, sets the default values
		if ( empty( $shiword_colors ) ) {
			$shiword_colors = array_merge( $this->default_device_colors , $this->default_device_bg );
			update_option( 'shiword_colors' , $shiword_colors );
		}

		if ( !empty( $_POST ) && check_admin_referer( 'custom-device-image' , '_wpnonce-custom-device-image' ) ) {

			$this->updated = true;
			
			if ( isset( $_POST['resetdevicecolor'] ) ) {
				remove_theme_mod( 'device_image' );
				set_theme_mod( 'device_color' , '0a0a0a' );
				return;
			}

			if ( isset( $_POST['removedeviceimage'] ) ) {
				$shiword_colors['device_image'] = '' ;
				update_option( 'shiword_colors' , $shiword_colors );
				return;
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
			$shiword_colors['devicecolor'] = $this->default_device_bg['device_color'];
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

			if ( isset($_POST['default-device-image']) ) {
				$this->process_default_device_images();
				if ( isset($this->default_device_images[$_POST['default-device-image']]) )
					$shiword_colors['device_image'] = esc_url( $this->default_device_images[$_POST['default-device-image']]['url'] );
			}
			
			foreach ( $this->default_device_colors as $key => $val ) {
			
				if ( isset( $_POST[$key] ) ) {
					$_POST[$key] = str_replace( '#' , '' , $_POST[$key] );
					$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $_POST[$key] );
					if ( strlen($color) == 6 || strlen($color) == 3 ) {
						$shiword_colors[$key] = '#' . $color ;
					} else {
						$shiword_colors[$key] = $val ;
					}
				} else {
					$shiword_colors[$key] = $val ;
				}
			}
			update_option( 'shiword_colors' , $shiword_colors );
		} else return;
	}

	/* Process the default headers */
	function process_default_device_images() {
		global $sw_default_device_images;

		if ( !empty($this->headers) )
			return;

		if ( !isset($sw_default_device_images) )
			return;

		$this->default_device_images = $sw_default_device_images;
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
			echo '<div class="default-device-image">';
			echo '<input class="default-device-input" name="default-device-image" type="radio" value="' . esc_attr($header_key) . '" ' . checked($header_url, $shiword_colors['device_image'], false) . ' />';
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
	var farbtastic;
	
	function showMeColorPicker(domid) {
		placeholder = '#shi_colorpicker_' + domid;
		jQuery(placeholder).show();
		farbtastic = jQuery.farbtastic(placeholder, function(color) { pickColor(domid,color); });
		farbtastic.setColor(jQuery('#shi_input_' + domid).val());
	}
	
	function pickColor(domid,color) {
		boxid = '#shi_box_' + domid;
		inputid = '#shi_input_' + domid;
		jQuery(boxid).css('background-color', color );
		jQuery(inputid).val(color);
		//farbtastic.setColor(color);
		updateShiPreview(domid,color);
	}
	
	function updateShiPreview(domid,color) {
		switch(domid)
		{
		case '1':
		  jQuery('#headimage').css('background-color', color );
		  break;
		case '2':
		  jQuery('#headimage a').css('color', color );
		  jQuery('#desc').css('color', color );
		  break;
		case 'main1':
		  jQuery('#preview-main').css('background-color', color );
		  break;
		case 'main2':
		  jQuery('#preview-body span').css('border-color', color );
		  break;
		case 'main3':
		  jQuery('#preview-body .preview-text').css('color', color );
		  break;
		case 'main4':
		  jQuery('#preview-body .preview-link').css('color', color );
		  break;
		case 'main5':
		  jQuery('#preview-body .preview-linkhi').css('color', color );
		  break;
		case 'meta1':
		  jQuery('#preview-meta').css('background-color', color );
		  jQuery('#preview-footer').css('background-color', color );
		  break;
		case 'meta2':
		  jQuery('#preview-footer span').css('border-color', color );
		  break;
		case 'meta3':
		  jQuery('#preview-meta .preview-text').css('color', color );
		  jQuery('#preview-footer .preview-text').css('color', color );
		  break;
		case 'meta4':
		  jQuery('#preview-meta .preview-link').css('color', color );
		  jQuery('#preview-footer .preview-link').css('color', color );
		  break;
		case 'meta5':
		  jQuery('#preview-meta .preview-linkhi').css('color', color );
		  jQuery('#preview-footer .preview-linkhi').css('color', color );
		  break;
		case 'meta6':
		  jQuery('#preview-meta').css('border-color', color );
		  jQuery('#preview-footer').css('border-color', color );
		  break;
		case 'menu1':
		  jQuery('#preview-menu').css('background-color', color );
		  jQuery('#preview-pages').css('background-color', color );
		  break;
		case 'menu2':
		  jQuery('#preview-menu span').css('border-color', color );
		  break;
		case 'menu3':
		  jQuery('#preview-menu .preview-text').css('color', color );
		  jQuery('#preview-pages .preview-text').css('color', color );
		  break;
		case 'menu4':
		  jQuery('#preview-menu .preview-link').css('color', color );
		  jQuery('#preview-pages .preview-link').css('color', color );
		  break;
		case 'menu5':
		  jQuery('#preview-menu .preview-linkhi').css('color', color );
		  jQuery('#preview-pages .preview-linkhi').css('color', color );
		  break;
		case 'menu6':
		  jQuery('#preview-menu').css('border-color', color );
		  jQuery('#preview-pages').css('border-color', color );
		  break;
		}
	}
	
	function secOpen(tableclass) {
		if ( tableclass == '.shi_bgc' ) {
			jQuery('.shi_cc').css( 'display','none' ); 
			jQuery(tableclass).toggle( 'slow' );
		}
		if ( tableclass == '.shi_cc' ) {
			jQuery('.shi_bgc').css( 'display','none' ); 
			jQuery(tableclass).toggle( 'slow' );
		}
	}
	
	jQuery(document).ready(function() {

		jQuery('.shi_input').keyup(function() {
			var _hex = jQuery(this).val();
			var hex = _hex;
			if ( hex[0] != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			hex = hex.substring(0,7);
			if ( hex != _hex )
				jQuery(this).val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				pickColor( jQuery(this).attr("id").replace('shi_input_', '') , hex );
			if ( hex.length == 1 )
				pickColor(  jQuery(this).attr("id").replace('shi_input_', '') , 'transparent' );
		});
		
		jQuery(document).mousedown(function(){
			jQuery('.shi_cp').each( function() {
				var display = jQuery(this).css('display');
				if (display == 'block')
					jQuery(this).fadeOut(2);
			});
		});

		jQuery('.default-device-input').click(function() {
			var cur_img = jQuery(this).parent().children('img').attr("src");
			jQuery('#headimage').css('background-image', 'url("' + cur_img + '")');
		});

		jQuery('.form-table').css( 'display','none' );
		
	});
</script>
<?php
	}

	function show_preview() {
			global $shiword_colors;

?>
					<div id="headimg-bg">
						<style type="text/css">
#headimage {
	background: <?php echo $shiword_colors['device_color']; ?> url('<?php echo $shiword_colors['device_image']; ?>') left top repeat;
}
#headimg_overlay a, #desc {
	color:<?php echo $shiword_colors['device_textcolor']; ?>;
}
#preview-main {
	background-color:<?php echo $shiword_colors['main1']; ?>;
}
#preview-body span {
	border-color:<?php echo $shiword_colors['main2']; ?>;
}
#preview-body .preview-text {
	color:<?php echo $shiword_colors['main3']; ?>;
}
#preview-body .preview-link {
	color:<?php echo $shiword_colors['main4']; ?>;
}
#preview-body .preview-linkhi {
	color:<?php echo $shiword_colors['main5']; ?>;
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
#preview-meta, #preview-footer {
	background-color:<?php echo $shiword_colors['meta1']; ?>;
	border-color:<?php echo $shiword_colors['meta2']; ?>;
}
#preview-footer span {
	border-color:<?php echo $shiword_colors['meta6']; ?>;
}
#preview-meta .preview-text, #preview-footer .preview-text {
	color:<?php echo $shiword_colors['meta3']; ?>;
}
#preview-meta .preview-link, #preview-footer .preview-link {
	color:<?php echo $shiword_colors['meta4']; ?>;
}
#preview-meta .preview-linkhi, #preview-footer .preview-linkhi {
	color:<?php echo $shiword_colors['meta5']; ?>;
}
						</style>
						<div id="headimage">
							<div id="headimg_overlay">
								<h1><a id="name" onclick="return false;" href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
								<div id="desc"><?php bloginfo( 'description' ); ?></div>
								
								
							
								<div id="preview-main">
									<div id="preview-pages">
										<span class="preview-text">text </span><span class="preview-link">link </span><span class="preview-linkhi">link hover</span>
									</div>
									<div id="preview-meta">
										<span class="preview-text">text </span><span class="preview-link">link </span><span class="preview-linkhi">link hover</span>
									</div>
									<div id="preview-body">
										<span class="preview-text">text </span><span class="preview-link">link </span><span class="preview-linkhi">link hover</span>
									</div>
									<div id="preview-footer">
										<span class="preview-text">text </span><span class="preview-link">link </span><span class="preview-linkhi">link hover</span>
									</div>
									<div id="preview-menu">
										<span class="preview-text">text </span><span class="preview-link">link </span><span class="preview-linkhi">link hover</span>
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div>
	
<?php	
	}
	
	/* Display first step of custom header image page. */
	function step_1() {
	
		global $shiword_colors;
		$shiword_colors = get_option( 'shiword_colors' );

		$this->process_default_device_images();
?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Custom Device Colors' , 'shiword' ); ?></h2>

	<?php if ( ! empty( $this->updated ) ) { ?>
		<div id="message" class="updated">
			<p><?php printf( __( 'Device colors updated. <a href="%s">Visit your site</a> to see how it looks.' , 'shiword' ) , home_url( '/' ) ); ?></p>
		</div>
	<?php } ?>

					<?php $this->show_preview(); ?>
	<h3 class="h3_field"><?php _e( 'Background Color' , 'shiword' ) ?> <a class="hide-if-no-js" href="#" onclick="secOpen('.shi_bgc'); return false;">&raquo;</a></h3>
	<table class="form-table shi_bgc">
		<tbody>
			<tr valign="top">
				<td style="width:200px;"><?php _e( 'Upload Image' ); ?></td>
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
		<table class="form-table shi_bgc">
			<tbody>
				<?php if ( ! empty( $this->default_device_images ) ) { ?>
					<tr valign="top">
						<td style="width:200px;"><?php _e( 'Default Images' ); ?></td>
						<td>
							<?php _e( 'If you don&lsquo;t want to upload your own image, you can use one of these images.' , 'shiword' ) ?>
							<?php $this->show_default_header_selector(); ?>
						</td>
					</tr>
				<?php }

				if ( $shiword_colors['device_image'] != '' ) { ?>
					<tr valign="top">
						<td style="width:200px;"><?php _e( 'Remove Image' ); ?></td>
						<td>
							<?php _e( 'This will remove the background image.' , 'shiword' ) ?>
							<input type="submit" class="button" name="removedeviceimage" value="<?php esc_attr_e( 'Remove Image' , 'shiword' ); ?>" />
						</td>
					</tr>
				<?php } ?>
				<tr valign="top">
					<td style="width:200px;"><?php _e( 'Background Color' , 'shiword' ) ?></td>
					<td>
						<input style="background-color:<?php echo $shiword_colors['device_color']; ?>;" class="color_preview_box" type="text" id="shi_box_1" value="" readonly="readonly" />
						<input type="text" name="devicecolor" id="shi_input_1" value="<?php echo $shiword_colors['device_color']; ?>" />
						<div class="shi_cp" id="shi_colorpicker_1" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
						<span class="hide-if-no-js">
							<a href="#" onclick="showMeColorPicker('1');return false;"><?php _e( 'Select a Color' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('1','transparent'); return false;"><?php _e( "set to \"transparent\"" , 'shiword' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="return false;"><?php _e( 'default color' , 'shiword' ); ?></a>
						</span>
					</td>
				</tr>

			</tbody>
		</table>
		<?php if ( $this->header_text() ) { ?>
			<table class="form-table shi_bgc">
				<tbody>
					<tr valign="top" id="text-color-row">
						<td style="width:200px;"><?php _e( 'Text Color' ); ?></td>
						<td>
							<input style="background-color:<?php echo $shiword_colors['device_textcolor']; ?>;" class="color_preview_box" type="text" id="shi_box_2" value="" readonly="readonly" />
							<input type="text" name="devicetextcolor" id="shi_input_2" value="<?php echo $shiword_colors['device_textcolor']; ?>" />
							<div class="shi_cp" id="shi_colorpicker_2" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
							<span class="hide-if-no-js">
								<a href="#" onclick="showMeColorPicker('2');return false;"><?php _e( 'Select a Color' ); ?></a>
								&nbsp;-&nbsp;
								<a href="#" onclick="pickColor('2','transparent'); return false;"><?php _e( "set to \"transparent\"" , 'shiword' ); ?></a>
								&nbsp;-&nbsp;
								<a href="#" onclick="return false;"><?php _e( 'default color' , 'shiword' ); ?></a>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
		<?php }
		?>
		
		<div id="" style="position: relative">
			<h3 class="h3_field"><?php _e( 'Content Colors' , 'shiword' ) ?> <a class="hide-if-no-js" href="#" onclick="secOpen('.shi_cc'); return false;">&raquo;</a></h3>
			<table class="form-table shi_cc">
			<?php foreach ( $this->default_device_colors as $key => $val ) { ?>
				<?php 
					if ( $key == 'main1' ) echo '<tr><td style="font-weight:bold; colspan="2">' . __( 'main content', 'shiword' ) . '</td></tr>';
					elseif ( $key == 'menu1' ) echo '<tr><td style="font-weight:bold; border-top:1px solid #CCCCCC;" colspan="2">' . __( 'pages menu and floating menu', 'shiword' ) . '</td></tr>';
					elseif ( $key == 'meta1' ) echo '<tr><td style="font-weight:bold; border-top:1px solid #CCCCCC;" colspan="2">' . __( 'meta fields and footer widget area', 'shiword' ) . '</td></tr>';
				?>
				<tr>
					<td style="width:200px;"><?php _e( $this->default_device_colors_descr[$key], 'shiword' ); ?></td>
					<td>
						<input style="background-color:<?php echo $shiword_colors[$key]; ?>;" class="color_preview_box" type="text" id="shi_box_<?php echo $key; ?>" value="" readonly="readonly" />
						<input id="shi_input_<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $shiword_colors[$key]; ?>" type="text" class="shi_input" />
						<div class="shi_cp" id="shi_colorpicker_<?php echo $key; ?>" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
						<span class="hide-if-no-js">
							<a href="#" onclick="showMeColorPicker('<?php echo $key; ?>');return false;"><?php _e( 'Select a Color' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('<?php echo $key; ?>','transparent'); return false;"><?php _e( "set to \"transparent\"" , 'shiword' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('<?php echo $key; ?>', '<?php echo $val; ?>' ); return false;"><?php _e( 'default color' , 'shiword' ); ?></a>
						</span>
					</td>
				</tr>
			<?php }	?>
			</table>
		</div>

		
		<?php
		wp_nonce_field( 'custom-device-image' , '_wpnonce-custom-device-image' ); ?>
		<p class="submit"><input type="submit" class="button-primary" name="save-device-options" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
	</form>

</div>

<?php }

	/* Run second step of custom header image page. */
	function step_2() {
		global $shiword_colors;
		check_admin_referer( 'custom-device-image' , '_wpnonce-custom-device-image' );
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

		$shiword_colors['device_image'] = esc_url($url);
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

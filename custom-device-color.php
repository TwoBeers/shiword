<?php
/* 
The custom colors script. 

Based on WP wp-admin/custom-header.php

*/

class Custom_device_color {

	/* Holds default outside colors. */
	var $default_device_bg = array(
		'device_image' => '',
		'device_color' => '#000',
		'device_textcolor' => '#fff',
		'device_button' => '#ff8d39'
	);

	/* Holds default headers. */
	var $default_device_images = array();

	/* Holds default inside colors. */
	var $default_device_colors = array(
		'main3' => '#21759b',
		'main4' => '#ff8d39',
		'menu1' => '#21759b',
		'menu2' => '#cccccc',
		'menu3' => '#262626',
		'menu4' => '#ffffff',
		'menu5' => '#ff8d39',
		'menu6' => '#cccccc',
	);
	
	/* Holds default inside colors descriptions */
	var $default_device_colors_descr = array(
		'menu1' => 'Background',
		'menu2' => 'Borders',
		'menu3' => 'Text',
		'menu4' => 'Links',
		'menu5' => 'Highlighted Links',
		'menu6' => 'Inner borders',
		'main3' => 'Links',
		'main4' => 'Highlighted Links',
	);

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
		$this->page = $page = add_theme_page(__( 'Custom Colors' ), __( 'Custom Colors' ), 'edit_theme_options' , 'device-color' , array( &$this , 'admin_page' ));
		add_action( "admin_print_scripts-$page" , array(&$this, 'js_includes' ) );
		add_action( "admin_print_styles-$page" , array(&$this, 'css_includes' ) );
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
		global $shiword_version;
		wp_enqueue_script('farbtastic');
		wp_enqueue_script( 'sw-custom-colors', get_template_directory_uri() . '/js/custom-colors.min.js', array('jquery'), $shiword_version, true ); //shiword js
	}

	/* Set up the enqueue for the CSS files */
	function css_includes() {
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style( 'shi_cdcpanel_style', get_template_directory_uri() . '/css/custom-device-color.css', false, '', 'screen' );
	}

	/* Options check. */
	function take_action() {
		if ( ! current_user_can( 'edit_theme_options' ) )
			return;
		
		$shiword_colors = get_option( 'shiword_colors' );
		//if options are empty, sets the default values
		if ( empty( $shiword_colors ) ) {
			$shiword_colors = array_merge( $this->default_device_colors , $this->default_device_bg );
			update_option( 'shiword_colors' , $shiword_colors );
		}
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

	// display the preview div
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
#preview-button {
	border-color:<?php echo $shiword_colors['device_button']; ?>;
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
						<div id="headimage">
							<div id="headimg_overlay">
								<h1><a id="name" onclick="return false;" href=""><?php bloginfo( 'name' ); ?></a></h1>
								<div id="desc"><?php bloginfo( 'description' ); ?></div>
								
							
								<div id="preview-main">
									<div id="preview-button"></div>
									<div id="preview-pages">
										<span class="preview-text"><?php _e( 'Text' ); ?> </span><span class="preview-link"><?php _e( 'Links' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
									</div>
									<div id="preview-meta">
										<span class="preview-text"><?php _e( 'Text' ); ?> </span><span class="preview-link"><?php _e( 'Links' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
									</div>
									<div id="preview-body">
										<span class="preview-text"><?php _e( 'Text' ); ?> </span><span class="preview-link"><?php _e( 'Links' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
									</div>
									<div id="preview-footer">
										<span class="preview-text"><?php _e( 'Text' ); ?> </span><span class="preview-link"><?php _e( 'Links' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
									</div>
									<div id="preview-menu">
										<span class="preview-text"><?php _e( 'Text' ); ?> </span><span class="preview-link"><?php _e( 'Links' ); ?> </span><span class="preview-linkhi"><?php _e( 'Highlighted Links' , 'shiword' ); ?></span>
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
		$shiword_colors = get_option( 'shiword_colors' );

		$this->process_default_device_images();
?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo get_current_theme() . ' - ' . __( 'Custom Colors' ); ?></h2>

	<?php if ( ! empty( $this->updated ) ) { ?>
		<div id="message" class="updated">
			<p><?php printf( __( 'Colors updated. <a href="%s">Visit your site</a> to see how it looks.' , 'shiword' ) , home_url( '/' ) ); ?></p>
		</div>
	<?php } ?>

					<?php $this->show_preview(); ?>
	<h3 class="h3_field"><?php _e( 'Exterior Colors' , 'shiword' ) ?> <a class="hide-if-no-js" href="#" onclick="secOpen('.shi_bgc'); return false;">&raquo;</a></h3>
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
							<?php wp_nonce_field( 'load-custom-image' , 'nonce-load-custom-image' ) ?>
							<input type="submit" class="button" value="<?php esc_attr_e( 'Upload' ); ?>" />
						</p>
					</form>
				</td>
			</tr>
		</tbody>
	</table>

	<form method="post" action="<?php echo esc_attr( add_query_arg( 'step' , 3 ) ) ?>">
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
							<input type="submit" class="button" name="removedeviceimage" value="<?php esc_attr_e( 'Remove Image' ); ?>" />
						</td>
					</tr>
				<?php } ?>
				<tr valign="top">
					<td style="width:200px;"><?php _e( 'Background Color' ) ?></td>
					<td>
						<input style="background-color:<?php echo $shiword_colors['device_color']; ?>;" class="color_preview_box" type="text" id="shi_box_1" value="" readonly="readonly" />
						<input type="text" name="devicecolor" id="shi_input_1" value="<?php echo $shiword_colors['device_color']; ?>" />
						<div class="shi_cp" id="shi_colorpicker_1" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
						<span class="hide-if-no-js">
							<a href="#" onclick="showMeColorPicker('1');return false;"><?php _e( 'Select a Color' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('1','transparent'); return false;"><?php _e( "Set to \"transparent\"" , 'shiword' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('1','<?php echo $this->default_device_bg['device_color']; ?>'); return false;"><?php _e( 'Default' , 'shiword' ); ?></a>
						</span>
					</td>
				</tr>
				<tr valign="top" id="text-color-row">
					<td style="width:200px;"><?php _e( 'Text Color' ); ?></td>
					<td>
						<input style="background-color:<?php echo $shiword_colors['device_textcolor']; ?>;" class="color_preview_box" type="text" id="shi_box_2" value="" readonly="readonly" />
						<input type="text" name="devicetextcolor" id="shi_input_2" value="<?php echo $shiword_colors['device_textcolor']; ?>" />
						<div class="shi_cp" id="shi_colorpicker_2" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
						<span class="hide-if-no-js">
							<a href="#" onclick="showMeColorPicker('2');return false;"><?php _e( 'Select a Color' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('2','transparent'); return false;"><?php _e( "Set to \"transparent\"" , 'shiword' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('2','<?php echo $this->default_device_bg['device_textcolor']; ?>'); return false;"><?php _e( 'Default' , 'shiword' ); ?></a>
						</span>
					</td>
				</tr>
				<tr valign="top" id="text-color-row">
					<td style="width:200px;"><?php _e( 'Hightlighted Buttons Border' , 'shiword' ); ?></td>
					<td>
						<input style="background-color:<?php echo $shiword_colors['device_button']; ?>;" class="color_preview_box" type="text" id="shi_box_3" value="" readonly="readonly" />
						<input type="text" name="devicebuttonborder" id="shi_input_3" value="<?php echo $shiword_colors['device_button']; ?>" />
						<div class="shi_cp" id="shi_colorpicker_3" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
						<span class="hide-if-no-js">
							<a href="#" onclick="showMeColorPicker('3');return false;"><?php _e( 'Select a Color' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('3','transparent'); return false;"><?php _e( "Set to \"transparent\"" , 'shiword' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('3','<?php echo $this->default_device_bg['device_button']; ?>'); return false;"><?php _e( 'Default' , 'shiword' ); ?></a>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div id="" style="position: relative">
			<h3 class="h3_field"><?php _e( 'Interior Colors' , 'shiword' ) ?> <a class="hide-if-no-js" href="#" onclick="secOpen('.shi_cc'); return false;">&raquo;</a></h3>
			<table class="form-table shi_cc">
			<?php foreach ( $this->default_device_colors as $key => $val ) { ?>
				<?php 
					if ( $key == 'main3' ) echo '<tr><td style="font-weight:bold; colspan="2">' . __( 'Main Content', 'shiword' ) . '</td></tr>';
					elseif ( $key == 'menu1' ) echo '<tr><td style="font-weight:bold; border-top:1px solid #CCCCCC;" colspan="2">' . __( 'Pages Menu and Floating Menu', 'shiword' ) . '</td></tr>';
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
							<a href="#" onclick="pickColor('<?php echo $key; ?>','transparent'); return false;"><?php _e( "Set to \"transparent\"" , 'shiword' ); ?></a>
							&nbsp;-&nbsp;
							<a href="#" onclick="pickColor('<?php echo $key; ?>', '<?php echo $val; ?>' ); return false;"><?php _e( 'Default' , 'shiword' ); ?></a>
						</span>
					</td>
				</tr>
			<?php }	?>
			</table>
		</div>

		
		<?php
		wp_nonce_field( 'set-custom-colors' , 'nonce-set-custom-colors' ); ?>
		<p class="submit"><input type="submit" class="button-primary" name="save-device-options" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
	</form>

</div>

<?php }

	/* Run second step of custom colors page - upload custom image. */
	function step_2() {
		global $shiword_colors;
		
		// exit if empty file list
		if( empty ($_FILES['import']['name']) ) return $this->step_1();
		
		check_admin_referer( 'load-custom-image' , 'nonce-load-custom-image' );
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
		$shiword_colors['device_button'] = $this->default_device_bg['device_button'];
		if ( isset( $_POST['devicebuttonborder'] ) ) {
			$_POST['devicebuttonborder'] = str_replace( '#' , '' , $_POST['devicebuttonborder'] );
			if ( 'transparent' == $_POST['devicebuttonborder'] ) {
				$shiword_colors['device_button'] = 'transparent';
			} else {
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $_POST['devicebuttonborder'] );
				if ( strlen($color) == 6 || strlen($color) == 3 )
					$shiword_colors['device_button'] = '#' . $color;
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
		elseif ( 3 == $step )
			$this->step_3();
	}

}
?>

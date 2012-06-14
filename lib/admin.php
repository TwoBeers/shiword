<?php
/**
 * The admin stuff
 *
 * @package Shiword
 * @since Shiword 3.0
 */

global $shiword_opt, $shiword_current_theme;

// Add custom menus
add_action( 'admin_menu', 'shiword_create_menu' );

// create custom theme settings menu
if ( !function_exists( 'shiword_create_menu' ) ) {
	function shiword_create_menu() {
		//create sub menu page to the Appearance menu - Theme Options
		$optionspage = add_theme_page( __( 'Theme Options', 'shiword' ), __( 'Theme Options', 'shiword' ), 'edit_theme_options', 'tb_shiword_functions', 'shiword_edit_options' );
		//call register settings function
		add_action( 'admin_init', 'shiword_register_settings' );
		//call custom stylesheet function
		add_action( 'admin_print_styles-widgets.php', 'shiword_widgets_style' );
		add_action( 'admin_print_scripts-widgets.php', 'shiword_widgets_scripts' );
		add_action( 'admin_print_styles-' . $optionspage, 'shiword_optionspage_style' );
		add_action( 'admin_print_scripts-' . $optionspage, 'shiword_optionspage_script' );
		add_action( 'admin_print_scripts', 'shiword_movegallerypage_script' );
	}
}

if ( !function_exists( 'shiword_widgets_style' ) ) {
	function shiword_widgets_style() {
		//add custom stylesheet
		wp_enqueue_style( 'sw-widgets-style', get_template_directory_uri() . '/css/admin-widgets.css', '', false, 'screen' );
	}
}

if ( !function_exists( 'shiword_optionspage_style' ) ) {
	function shiword_optionspage_style() {
		//add custom stylesheet
		wp_enqueue_style( 'sw-options-style', get_template_directory_uri() . '/css/admin-options.css', array( 'farbtastic' ), false, 'screen' );
	}
}

if ( !function_exists( 'shiword_widgets_scripts' ) ) {
	function shiword_widgets_scripts() {
		global $shiword_version;
		wp_enqueue_script( 'sw-widgets-script', get_template_directory_uri() . '/js/admin-widgets.dev.js', array('jquery'), $shiword_version, true );
	}
}

if ( !function_exists( 'shiword_movegallerypage_script' ) ) {
	function shiword_movegallerypage_script() {
		global $shiword_version;
		wp_enqueue_script( 'sw-movegallerypage-script', get_template_directory_uri() . '/js/admin-movegallerypage.dev.js', array('jquery'), $shiword_version, true );
	}
}

if ( !function_exists( 'shiword_optionspage_script' ) ) {
	function shiword_optionspage_script() {
		global $shiword_version;
		wp_enqueue_script( 'sw-options-script', get_template_directory_uri() . '/js/admin-options.dev.js', array( 'jquery', 'farbtastic' ), $shiword_version, true ); //shiword js
		$data = array(
			'confirm_to_defaults' => __( 'Are you really sure you want to set all the options to their default values?', 'shiword' )
		);
		wp_localize_script( 'sw-options-script', 'sw_l10n', $data );
	}
}

// print a reminder message for set the options after the theme is installed
if ( !function_exists( 'shiword_setopt_admin_notice' ) ) {
	function shiword_setopt_admin_notice() {
		echo '<div class="updated"><p><strong>' . sprintf( __( "Shiword theme says: \"Don't forget to set <a href=\"%s\">my options</a> and the header image!\" ", 'shiword' ), get_admin_url() . 'themes.php?page=tb_shiword_functions' ) . '</strong></p></div>';
	}
}

if ( current_user_can( 'manage_options' ) && $shiword_opt['version'] < $shiword_current_theme['Version'] ) {
	add_action( 'admin_notices', 'shiword_setopt_admin_notice' );
}

if ( !function_exists( 'shiword_register_settings' ) ) {
	function shiword_register_settings() {
		//register general settings
		register_setting( 'shiw_settings_group', 'shiword_options', 'shiword_sanitize_options' );
		//register colors settings
		register_setting( 'shiw_colors_group', 'shiword_colors'  );
	}
}


// check and set default options 
function shiword_default_options() {
		global $shiword_current_theme;
		$shiword_opt = get_option( 'shiword_options' );
		$shiword_coa = shiword_get_coa();

		// if options are empty, sets the default values
		if ( empty( $shiword_opt ) || !isset( $shiword_opt ) ) {
			foreach ( $shiword_coa as $key => $val ) {
				$shiword_opt[$key] = $shiword_coa[$key]['default'];
			}
			$shiword_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'shiword_options' , $shiword_opt );
		} else if ( !isset( $shiword_opt['version'] ) || $shiword_opt['version'] < $shiword_current_theme['Version'] ) {
			// check for unset values and set them to default value -> when updated to new version
			foreach ( $shiword_coa as $key => $val ) {
				if ( !isset( $shiword_opt[$key] ) ) $shiword_opt[$key] = $shiword_coa[$key]['default'];
			}
			$shiword_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'shiword_options' , $shiword_opt );
		}
}

// sanitize options value
if ( !function_exists( 'shiword_sanitize_options' ) ) {
	function shiword_sanitize_options( $input ){
		global $shiword_current_theme;
		$shiword_coa = shiword_get_coa();
		// check for updated values and return 0 for disabled ones <- index notice prevention
		foreach ( $shiword_coa as $key => $val ) {

			if( $shiword_coa[$key]['type'] == 'chk' ) {
				if( !isset( $input[$key] ) ) {
					$input[$key] = 0;
				} else {
					$input[$key] = ( $input[$key] == 1 ? 1 : 0 );
				}
			} elseif( $shiword_coa[$key]['type'] == 'sel' ) {
				if ( !in_array( $input[$key], $shiword_coa[$key]['options'] ) ) $input[$key] = $shiword_coa[$key]['default'];
			} elseif( $shiword_coa[$key]['type'] == 'opt' ) {
				if ( !in_array( $input[$key], $shiword_coa[$key]['options'] ) ) $input[$key] = $shiword_coa[$key]['default'];
			} elseif( $shiword_coa[$key]['type'] == 'txt' ) {
				if( !isset( $input[$key] ) ) {
					$input[$key] = '';
				} else {
					$input[$key] = trim( strip_tags( $input[$key] ) );
				}
			} elseif( $shiword_coa[$key]['type'] == 'txtarea' ) {
				if( !isset( $input[$key] ) ) {
					$input[$key] = '';
				} else {
					$input[$key] = trim( strip_tags( $input[$key] ) );
				}
			} elseif( $shiword_coa[$key]['type'] == 'int' ) {
				if( !isset( $input[$key] ) ) {
					$input[$key] = $shiword_coa[$key]['default'];
				} else {
					$input[$key] = (int) $input[$key] ;
				}
			} elseif( $shiword_coa[$key]['type'] == 'col' ) {
				$color = str_replace( '#' , '' , $input[$key] );
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $color );
				$input[$key] = '#' . $color;
			}
		}
		// check for required options
		foreach ( $shiword_coa as $key => $val ) {
			if ( $shiword_coa[$key]['req'] != '' ) { if ( $input[$shiword_coa[$key]['req']] == 0 ) $input[$key] = 0; }
		}
		$input['version'] = $shiword_current_theme['Version']; // keep version number
		return $input;
	}
}

// the option page
if ( !function_exists( 'shiword_edit_options' ) ) {
	function shiword_edit_options() {
		if ( !current_user_can( 'edit_theme_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'shiword' ) );
		}
		global $shiword_opt, $shiword_current_theme;
		
		if ( isset( $_GET['erase'] ) && ! isset( $_REQUEST['settings-updated'] ) ) {
			delete_option( 'shiword_options' );
			shiword_default_options();
			$shiword_opt = get_option( 'shiword_options' );
		}

		$shiword_coa = shiword_get_coa();
		
		// update version value when admin visit options page
		if ( $shiword_opt['version'] < $shiword_current_theme['Version'] ) {
			$shiword_opt['version'] = $shiword_current_theme['Version'];
			update_option( 'shiword_options' , $shiword_opt );
		}
	?>
		<div class="wrap">
			<div class="icon32" id="sw-icon"><br></div>
			<h2><?php echo get_current_theme() . ' - ' . __( 'Theme Options', 'shiword' ); ?></h2>
			<?php
				// return options save message
				if ( isset( $_REQUEST['settings-updated'] ) ) {
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Options saved.', 'shiword' ) . '</strong></p></div>';
				}
				// return options save message
				if ( isset( $_GET['erase'] ) && ! isset( $_REQUEST['settings-updated'] ) ) {
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Defaults values loaded.', 'shiword' ) . '</strong></p></div>';
				}
			?>
			<div id="tabs-container">
				<ul id="selector">
					<li id="shiword-options-li">
						<a href="#shiword-options" onClick="shiwordOptions.switchSection(); return false;"><span class="wp-menu-image" style="background-image: url('<?php echo get_admin_url() . 'images/menu.png' ?>')"> </span><?php _e( 'Theme features' , 'shiword' ); ?></a>
					</li>
					<li id="shiword-infos-li">
						<a href="#shiword-infos" onClick="shiwordOptions.switchSection(); return false;"><span class="wp-menu-image" style="background-image: url('<?php echo get_admin_url() . 'images/menu.png' ?>')"> </span><?php _e( 'Info', 'shiword' ); ?></a>
					</li>
				</ul>
				<div class="clear"></div>
				<div id="shiword-options">
					<form method="post" action="options.php">
						<?php settings_fields( 'shiw_settings_group' ); ?>
						<ul id="sw-tabselector" class="hide-if-no-js">
							<li class="sw-selgroup-other"><a href="#" onClick="shiwordOptions.switchTab('other'); return false;"><?php _e( 'other' , 'shiword' ); ?></a></li>
							<li class="sw-selgroup-mobile"><a href="#" onClick="shiwordOptions.switchTab('mobile'); return false;"><?php _e( 'mobile' , 'shiword' ); ?></a></li>
							<li class="sw-selgroup-slideshow"><a href="#" onClick="shiwordOptions.switchTab('slideshow'); return false;"><?php _e( 'slideshow' , 'shiword' ); ?></a></li>
							<li class="sw-selgroup-sidebar"><a href="#" onClick="shiwordOptions.switchTab('sidebar'); return false;"><?php _e( 'sidebar' , 'shiword' ); ?></a></li>
							<li class="sw-selgroup-postformats"><a href="#" onClick="shiwordOptions.switchTab('postformats'); return false;"><?php _e( 'post formats' , 'shiword' ); ?></a></li>
							<li class="sw-selgroup-fonts"><a href="#" onClick="shiwordOptions.switchTab('fonts'); return false;"><?php _e( 'fonts' , 'shiword' ); ?></a></li>
							<li class="sw-selgroup-content"><a href="#" onClick="shiwordOptions.switchTab('content'); return false;"><?php _e( 'content' , 'shiword' ); ?></a></li>
							<li class="sw-selgroup-navigation"><a href="#" onClick="shiwordOptions.switchTab('navigation'); return false;"><?php _e( 'navigation' , 'shiword' ); ?></a></li>
						</ul>
						<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Options','shiword' ); ?></h2>
						<?php foreach ( $shiword_coa as $key => $val ) { ?>
							<?php if ( isset( $shiword_coa[$key]['sub'] ) && !$shiword_coa[$key]['sub'] ) continue; ?>
							<div class="sw-tab-opt sw-tabgroup-<?php echo $shiword_coa[$key]['group']; ?>">
								<span class="column-nam"><?php echo $shiword_coa[$key]['description']; ?></span>
							<?php if ( !isset ( $shiword_opt[$key] ) ) $shiword_opt[$key] = $shiword_coa[$key]['default']; ?>
							<?php if ( $shiword_coa[$key]['type'] == 'chk' ) { ?>
								<input name="shiword_options[<?php echo $key; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $shiword_opt[$key] ); ?> />
							<?php } elseif ( ( $shiword_coa[$key]['type'] == 'txt' ) || ( $shiword_coa[$key]['type'] == 'int' ) ) { ?>
								<input name="shiword_options[<?php echo $key; ?>]" value="<?php echo $shiword_opt[$key]; ?>" type="text" />
							<?php } elseif ( $shiword_coa[$key]['type'] == 'txtarea' ) { ?>
								<textarea name="shiword_options[<?php echo $key; ?>]"><?php echo $shiword_opt[$key]; ?></textarea>
							<?php } elseif ( $shiword_coa[$key]['type'] == 'sel' ) { ?>
								<select name="shiword_options[<?php echo $key; ?>]">
								<?php foreach( $shiword_coa[$key]['options'] as $optionkey => $option ) { ?>
									<option value="<?php echo $option; ?>" <?php selected( $shiword_opt[$key], $option ); ?>><?php echo $shiword_coa[$key]['options_readable'][$optionkey]; ?></option>
								<?php } ?>
								</select>
							<?php } elseif ( $shiword_coa[$key]['type'] == 'opt' ) { ?>
								<?php foreach( $shiword_coa[$key]['options'] as $optionkey => $option ) { ?>
									<label title="<?php echo esc_attr($option); ?>"><input type="radio" <?php checked( $shiword_opt[$key], $option ); ?> value="<?php echo $option; ?>" name="shiword_options[<?php echo $key; ?>]"> <span><?php echo $shiword_coa[$key]['options_readable'][$optionkey]; ?></span></label>
								<?php } ?>
							<?php } elseif ( $shiword_coa[$key]['type'] == 'col' ) { ?>
								<input class="sw-color" style="background-color:<?php echo $shiword_opt[$key]; ?>;" onclick="shiwordOptions.showColorPicker('<?php echo $key; ?>');" id="sw-color-<?php echo $key; ?>" type="text" name="shiword_options[<?php echo $key; ?>]" value="<?php echo $shiword_opt[$key]; ?>" />
								<div class="sw-colorpicker" id="sw-colorpicker-<?php echo $key; ?>"></div>
							<?php }	?>
								<?php if ( $shiword_coa[$key]['req'] != '' ) { ?><div class="column-req"><?php echo '<u>' . __('requires','shiword') . '</u>: ' . $shiword_coa[$shiword_coa[$key]['req']]['description']; ?></div><?php } ?>
								<div class="column-des"><?php echo $shiword_coa[$key]['info']; ?></div>
							<?php if ( isset( $shiword_coa[$key]['sub'] ) ) { ?>
									<div class="sw-sub-opt">
								<?php foreach ( $shiword_coa[$key]['sub'] as $subkey => $subval ) { ?>
									<?php if ( !isset ($shiword_opt[$subval]) ) $shiword_opt[$subval] = $shiword_coa[$subval]['default']; ?>
									<?php if ( $shiword_coa[$subval]['type'] == 'chk' ) { ?>
										<input name="shiword_options[<?php echo $subval; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $shiword_opt[$subval] ); ?> />
										<span class="sw-sub-opt-nam"><?php echo $shiword_coa[$subval]['description']; ?></span>
									<?php } elseif ( ( $shiword_coa[$subval]['type'] == 'txt' ) || ( $shiword_coa[$subval]['type'] == 'int' ) ) { ?>
										<span class="sw-sub-opt-nam"><?php echo $shiword_coa[$subval]['description']; ?></span> :
										<input name="shiword_options[<?php echo $subval; ?>]" value="<?php echo $shiword_opt[$subval]; ?>" type="text" />
									<?php } elseif ( $shiword_coa[$subval]['type'] == 'sel' ) { ?>
										<span class="sw-sub-opt-nam"><?php echo $shiword_coa[$subval]['description']; ?></span> :
										<select name="shiword_options[<?php echo $subval; ?>]">
										<?php foreach( $shiword_coa[$subval]['options'] as $optionkey => $option ) { ?>
											<option value="<?php echo $option; ?>" <?php selected( $shiword_opt[$subval], $option ); ?>><?php echo $shiword_coa[$subval]['options_readable'][$optionkey]; ?></option>
										<?php } ?>
										</select>
									<?php } elseif ( $shiword_coa[$subval]['type'] == 'opt' ) { ?>
										<span class="sw-sub-opt-nam"><?php echo $shiword_coa[$subval]['description']; ?></span> :
										<?php foreach( $shiword_coa[$subval]['options'] as $optionkey => $option ) { ?>
											<label title="<?php echo esc_attr($option); ?>"><input type="radio" <?php checked( $shiword_opt[$subval], $option ); ?> value="<?php echo $option; ?>" name="shiword_options[<?php echo $subval; ?>]"> <span><?php echo $shiword_coa[$subval]['options_readable'][$optionkey]; ?></span></label>
										<?php } ?>
									<?php } elseif ( $shiword_coa[$subval]['type'] == 'col' ) { ?>
										<span class="sw-sub-opt-nam"><?php echo $shiword_coa[$subval]['description']; ?></span> :
										<input class="sw-color" style="background-color:<?php echo $shiword_opt[$subval]; ?>;" onclick="shiwordOptions.showColorPicker('<?php echo $subval; ?>');" id="sw-color-<?php echo $subval; ?>" type="text" name="shiword_options[<?php echo $subval; ?>]" value="<?php echo $shiword_opt[$subval]; ?>" />
										<div class="sw-colorpicker" id="sw-colorpicker-<?php echo $subval; ?>"></div>
									<?php }	?>
									<?php if ( $shiword_coa[$subval]['info'] != '' ) { ?> - <span class="sw-sub-opt-des"><?php echo $shiword_coa[$subval]['info']; ?></span><?php } ?>
									</br>
								<?php }	?>
									</div>
							<?php }	?>
							</div>
						<?php }	?>
						<div id="sw-submit">
							<input type="hidden" name="shiword_options[hidden_opt]" value="default" />
							<input class="button-primary" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'shiword' ); ?>" />
							<a href="themes.php?page=tb_shiword_functions" target="_self"><?php _e( 'Undo Changes' , 'shiword' ); ?></a>
							|
							<a id="to-defaults" href="themes.php?page=tb_shiword_functions&erase=1" target="_self"><?php _e( 'Back to defaults' , 'shiword' ); ?></a>
						</div>
					</form>
				</div>
				<div id="shiword-infos">
					<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Info','shiword' ); ?></h2>
					<?php locate_template( 'readme.html',true ); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="stylediv" style="clear: both; text-align: center; border: 1px solid #ccc;">
				<small>
					<?php _e( 'If you like/dislike this theme, or if you encounter any issues, please let us know it.', 'shiword' ); ?><br />
					<a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/shiword' ); ?>" title="Shiword theme" target="_blank"><?php _e( 'Leave a feedback', 'shiword' ); ?></a>
				</small>
			</div>
			<div class="stylediv" style="clear: both; text-align: center; border: 1px solid #ccc; margin-top: 10px;">
				<small>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/temi-wp/wordpress-themes-translations' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
			</div>
		</div>
		<?php
	}
}

//load custom colors
$shiword_colors = shiword_get_colors();

function shiword_get_default_colors($type) {
	// Holds default outside colors
	$shiword_default_device_bg = array(
		'device_image' => '',
		'device_color' => '#000000',
		'device_opacity' => '100',
		'device_textcolor' => '#ffffff',
		'device_button' => '#ff8d39',
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

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'shiword_admin_header_style' ) ) {
	function shiword_admin_header_style() {
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/admin-custom_header.css" />' . "\n";
	}
}

// Register a selection of default images to be displayed as device backgrounds by the custom device color admin UI. based on WP theme.php -> register_default_headers()
if ( !function_exists( 'shiword_register_default_device_images' ) ) {
	function shiword_register_default_device_images( $headers ) {
		global $shiword_default_device_images;

		$shiword_default_device_images = array_merge( (array) $shiword_default_device_images , (array) $headers );
	}
}

// Add callbacks for device color display. based on WP theme.php -> add_custom_image_header()
if ( !function_exists( 'shiword_add_custom_device_image' ) ) {
	function shiword_add_custom_device_image() {
		if ( ! is_admin() )
			return;
		require_once( 'custom-device-color.php' );
		$GLOBALS['custom_device_color'] =& new Custom_device_color();
		add_action( 'admin_menu' , array(&$GLOBALS['custom_device_color'] , 'init' ));
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
		require_once( 'my-custom-background.php' );
		$GLOBALS['custom_background'] =& new Custom_Background( $admin_header_callback, $admin_image_div_callback );
		add_action( 'admin_menu', array( &$GLOBALS['custom_background'], 'init' ) );
	}
}

//Add new contact methods to author panel
if ( !function_exists( 'shiword_new_contactmethods' ) ) {
	function shiword_new_contactmethods( $contactmethods ) {
		//add Twitter
		$contactmethods['twitter'] = 'Twitter';
		//add Facebook
		$contactmethods['facebook'] = 'Facebook';

		return $contactmethods;
	}
}

//add a default gravatar
if ( !function_exists( 'shiword_addgravatar' ) ) {
	function shiword_addgravatar( $avatar_defaults ) {
	  $myavatar = get_template_directory_uri() . '/images/user.png';
	  $avatar_defaults[$myavatar] = __( 'shiword Default Gravatar', 'shiword' );

	  return $avatar_defaults;
	}
}

?>
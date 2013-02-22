<?php
/**
 * The admin stuff
 *
 * @package Shiword
 * @since Shiword 3.0
 */

global $shiword_opt, $shiword_version;

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

//add custom stylesheet
if ( !function_exists( 'shiword_widgets_style' ) ) {
	function shiword_widgets_style() {

		wp_enqueue_style( 'sw-widgets-style', get_template_directory_uri() . '/css/admin-widgets.css', '', false, 'screen' );

	}
}

//add custom stylesheet
if ( !function_exists( 'shiword_optionspage_style' ) ) {
	function shiword_optionspage_style() {

		wp_enqueue_style( 'sw-options-style', get_template_directory_uri() . '/css/admin-options.css', array( 'farbtastic' ), false, 'screen' );

	}
}

if ( !function_exists( 'shiword_widgets_scripts' ) ) {
	function shiword_widgets_scripts() {
		global $shiword_version;

		wp_enqueue_script( 'sw-widgets-script', get_template_directory_uri() . '/js/admin-widgets.dev.js', array('jquery'), $shiword_version, true );

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

if ( current_user_can( 'manage_options' ) && $shiword_opt['version'] < $shiword_version ) {
	add_action( 'admin_notices', 'shiword_setopt_admin_notice' );
}

if ( !function_exists( 'shiword_register_settings' ) ) {
	function shiword_register_settings() {

		register_setting( 'shiword_settings_group', 'shiword_options', 'shiword_sanitize_options' ); //register general settings

		register_setting( 'shiword_colors_group', 'shiword_colors'  ); //register colors settings

	}
}


// check and set default options 
function shiword_default_options() {
		global $shiword_version;

		$the_opt = get_option( 'shiword_options' );
		$the_coa = shiword_get_coa();

		// if options are empty, sets the default values
		if ( empty( $the_opt ) || !isset( $the_opt ) ) {
			foreach ( $the_coa as $key => $val ) {
				$the_opt[$key] = $the_coa[$key]['default'];
			}
			$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'shiword_options' , $the_opt );
		} else if ( !isset( $the_opt['version'] ) || $the_opt['version'] < $shiword_version ) {
			// check for unset values and set them to default value -> when updated to new version
			foreach ( $the_coa as $key => $val ) {
				if ( !isset( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['default'];
			}
			$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'shiword_options' , $the_opt );

		}
}

// sanitize options value
if ( !function_exists( 'shiword_sanitize_options' ) ) {
	function shiword_sanitize_options( $input ){
		global $shiword_version;

		$the_coa = shiword_get_coa();

		foreach ( $the_coa as $key => $val ) {

			switch ( $the_coa[$key]['type'] ) {

				case 'chk':		// checkbox
					if( !isset( $input[$key] ) ) {
						$input[$key] = 0;
					} else {
						$input[$key] = ( $input[$key] == 1 ? 1 : 0 );
					}
					break;

				case 'sel':		// select
					if ( !in_array( $input[$key], $the_coa[$key]['options'] ) ) $input[$key] = $the_coa[$key]['default'];
					break;

				case 'opt':		// radio
					if ( !in_array( $input[$key], $the_coa[$key]['options'] ) ) $input[$key] = $the_coa[$key]['default'];
					break;

				case 'txt':		// text
					if( !isset( $input[$key] ) ) {
						$input[$key] = '';
					} else {
						$input[$key] = trim( strip_tags( $input[$key] ) );
					}
					break;

				case 'txtarea':	// textarea
					if( !isset( $input[$key] ) ) {
						$input[$key] = '';
					} else {
						$input[$key] = trim( strip_tags( $input[$key] ) );
					}
					break;

				case 'int':		// integer
					if( !isset( $input[$key] ) ) {
						$input[$key] = $the_coa[$key]['default'];
					} else {
						$input[$key] = (int) $input[$key] ;
					}
					break;

				case 'col':		// color
					$color = str_replace( '#' , '' , $input[$key] );
					$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $color );
					$input[$key] = '#' . $color;
					break;

				default:
					// nop
			}
		}
		// check for required options
		foreach ( $the_coa as $key => $val ) {
			if ( $the_coa[$key]['req'] != '' ) { if ( $input[$the_coa[$key]['req']] == 0 ) $input[$key] = 0; }
		}
		$input['version'] = $shiword_version; // keep version number
		return $input;

	}
}

// the option page
if ( !function_exists( 'shiword_edit_options' ) ) {
	function shiword_edit_options() {
		global $shiword_opt, $shiword_version, $shiword_current_theme;

		if ( !current_user_can( 'edit_theme_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'shiword' ) );
		}

		$the_coa = shiword_get_coa();
		$the_groups = shiword_get_coa( 'groups' );
		$the_option_name = 'shiword_options';

		if ( isset( $_GET['erase'] ) ) {
			$_SERVER['REQUEST_URI'] = remove_query_arg( 'erase', $_SERVER['REQUEST_URI'] );
			delete_option( $the_option_name );
			shiword_default_options();
			$shiword_opt = get_option( $the_option_name );
		}

		// update version value when admin visit options page
		if ( $shiword_opt['version'] < $shiword_version ) {
			$shiword_opt['version'] = $shiword_version;
			update_option( $the_option_name , $shiword_opt );
		}

		$the_opt = $shiword_opt;

	?>
		<div class="wrap">
			<div class="icon32" id="sw-icon"><br></div>
			<h2><?php echo $shiword_current_theme . ' - ' . __( 'Theme Options', 'shiword' ); ?></h2>
			<?php
				// options have been updated
				if ( isset( $_REQUEST['settings-updated'] ) ) {
					//return options save message
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Options saved.','shiword' ) . '</strong></p></div>';
				}

				// options to defaults done
				if ( isset( $_GET['erase'] ) ) {
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
						<?php settings_fields( 'shiword_settings_group' ); ?>
						<ul id="sw-tabselector" class="hide-if-no-js">
						<?php foreach( $the_groups as $key => $name ) { ?>
							<li id="selgroup-<?php echo $key; ?>"><a href="#" onClick="shiwordOptions.switchTab('<?php echo $key; ?>'); return false;"><?php echo $name; ?></a></li>
						<?php } ?>
						</ul>
						<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Options','shiword' ); ?></h2>
						<?php foreach ( $the_coa as $key => $val ) { ?>
							<?php if ( isset( $the_coa[$key]['sub'] ) && !$the_coa[$key]['sub'] ) continue; ?>
							<div class="sw-tab-opt sw-tabgroup-<?php echo $the_coa[$key]['group']; ?>">
								<span class="column-nam"><?php echo $the_coa[$key]['description']; ?></span>
							<?php if ( !isset ( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['default']; ?>
							<?php if ( $the_coa[$key]['type'] == 'chk' ) { ?>
								<input name="shiword_options[<?php echo $key; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $the_opt[$key] ); ?> />
							<?php } elseif ( ( $the_coa[$key]['type'] == 'txt' ) || ( $the_coa[$key]['type'] == 'int' ) ) { ?>
								<input name="shiword_options[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" type="text" />
							<?php } elseif ( $the_coa[$key]['type'] == 'txtarea' ) { ?>
								<textarea name="shiword_options[<?php echo $key; ?>]"><?php echo $the_opt[$key]; ?></textarea>
							<?php } elseif ( $the_coa[$key]['type'] == 'sel' ) { ?>
								<select name="shiword_options[<?php echo $key; ?>]">
								<?php foreach( $the_coa[$key]['options'] as $optionkey => $option ) { ?>
									<option value="<?php echo $option; ?>" <?php selected( $the_opt[$key], $option ); ?>><?php echo $the_coa[$key]['options_readable'][$optionkey]; ?></option>
								<?php } ?>
								</select>
							<?php } elseif ( $the_coa[$key]['type'] == 'opt' ) { ?>
								<?php foreach( $the_coa[$key]['options'] as $optionkey => $option ) { ?>
									<label title="<?php echo esc_attr($option); ?>"><input type="radio" <?php checked( $the_opt[$key], $option ); ?> value="<?php echo $option; ?>" name="shiword_options[<?php echo $key; ?>]"> <span><?php echo $the_coa[$key]['options_readable'][$optionkey]; ?></span></label>
								<?php } ?>
							<?php } elseif ( $the_coa[$key]['type'] == 'col' ) { ?>
								<input class="sw-color" style="background-color:<?php echo $the_opt[$key]; ?>;" onclick="shiwordOptions.showColorPicker('<?php echo $key; ?>');" id="sw-color-<?php echo $key; ?>" type="text" name="shiword_options[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" />
								<div class="sw-colorpicker" id="sw-colorpicker-<?php echo $key; ?>"></div>
							<?php }	?>
								<?php if ( $the_coa[$key]['req'] != '' ) { ?><div class="column-req"><?php echo '<u>' . __('requires','shiword') . '</u>: ' . $the_coa[$the_coa[$key]['req']]['description']; ?></div><?php } ?>
								<div class="column-des"><?php echo $the_coa[$key]['info']; ?></div>
							<?php if ( isset( $the_coa[$key]['sub'] ) ) { ?>
									<div class="sw-sub-opt">
								<?php foreach ( $the_coa[$key]['sub'] as $subkey => $subval ) { ?>
									<?php if ( $subval == '' ) { echo '<br>'; continue; } ?>
									<?php if ( !isset ($the_opt[$subval]) ) $the_opt[$subval] = $the_coa[$subval]['default']; ?>
									<?php if ( $the_coa[$subval]['type'] == 'chk' ) { ?>
										<input name="shiword_options[<?php echo $subval; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $the_opt[$subval] ); ?> />
										<span class="sw-sub-opt-nam"><?php echo $the_coa[$subval]['description']; ?></span>
									<?php } elseif ( ( $the_coa[$subval]['type'] == 'txt' ) || ( $the_coa[$subval]['type'] == 'int' ) ) { ?>
										<span class="sw-sub-opt-nam"><?php echo $the_coa[$subval]['description']; ?></span> :
										<input name="shiword_options[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" type="text" />
									<?php } elseif ( $the_coa[$subval]['type'] == 'sel' ) { ?>
										<span class="sw-sub-opt-nam"><?php echo $the_coa[$subval]['description']; ?></span> :
										<select name="shiword_options[<?php echo $subval; ?>]">
										<?php foreach( $the_coa[$subval]['options'] as $optionkey => $option ) { ?>
											<option value="<?php echo $option; ?>" <?php selected( $the_opt[$subval], $option ); ?>><?php echo $the_coa[$subval]['options_readable'][$optionkey]; ?></option>
										<?php } ?>
										</select>
									<?php } elseif ( $the_coa[$subval]['type'] == 'opt' ) { ?>
										<span class="sw-sub-opt-nam"><?php echo $the_coa[$subval]['description']; ?></span> :
										<?php foreach( $the_coa[$subval]['options'] as $optionkey => $option ) { ?>
											<label title="<?php echo esc_attr($option); ?>"><input type="radio" <?php checked( $the_opt[$subval], $option ); ?> value="<?php echo $option; ?>" name="shiword_options[<?php echo $subval; ?>]"> <span><?php echo $the_coa[$subval]['options_readable'][$optionkey]; ?></span></label>
										<?php } ?>
									<?php } elseif ( $the_coa[$subval]['type'] == 'col' ) { ?>
										<span class="sw-sub-opt-nam"><?php echo $the_coa[$subval]['description']; ?></span> :
										<input class="sw-color" style="background-color:<?php echo $the_opt[$subval]; ?>;" onclick="shiwordOptions.showColorPicker('<?php echo $subval; ?>');" id="sw-color-<?php echo $subval; ?>" type="text" name="shiword_options[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" />
										<div class="sw-colorpicker" id="sw-colorpicker-<?php echo $subval; ?>"></div>
									<?php }	?>
									<?php if ( $the_coa[$subval]['info'] != '' ) { ?> - <span class="sw-sub-opt-des"><?php echo $the_coa[$subval]['info']; ?></span><?php } ?>
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
			<div class="stylediv">
				<small>
					<?php _e( 'If you like/dislike this theme, or if you encounter any issues, please let us know it.', 'shiword' ); ?><br />
					<a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/shiword' ); ?>" title="Shiword theme" target="_blank"><?php _e( 'Leave a feedback', 'shiword' ); ?></a>
				</small>
			</div>
			<div class="stylediv">
				<small>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/temi-wp/wordpress-themes-translations' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
			</div>
		</div>
		<?php

	}
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'shiword_admin_header_style' ) ) {
	function shiword_admin_header_style() {

		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/admin-custom_header.css" />' . "\n";

	}
}

//Add new contact methods to author panel
if ( !function_exists( 'shiword_new_contactmethods' ) ) {
	function shiword_new_contactmethods( $contactmethods ) {

		$contactmethods['twitter'] = 'Twitter'; //add Twitter

		$contactmethods['facebook'] = 'Facebook'; //add Facebook

		$contactmethods['google'] = 'Google+'; //add Google+

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
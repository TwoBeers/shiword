<?php
/**
 * The posts slider
 *
 * @package Shiword
 * @since Shiword 3.1
 */
 
//add_action( 'wp_footer', 'shiword_slider_init' ); //include the initialize code in footer


// Add custom menus
add_action( 'admin_menu', 'shiword_slider_create_menu' );

// create custom theme settings menu
if ( !function_exists( 'shiword_slider_create_menu' ) ) {
	function shiword_slider_create_menu() {
		//create sub menu page to the Appearance menu - Slideshow
		$slidepage = add_theme_page( __( 'Slideshow', 'shiword' ), __( 'Slideshow', 'shiword' ), 'edit_theme_options', 'tb_shiword_slideshow', 'shiword_slider_edit' );
		//call register settings function
		add_action( 'admin_init', 'shiword_slider_register_settings' );
		//call custom stylesheet function
		add_action( 'admin_print_styles-' . $slidepage, 'shiword_slider_page_style' );
		add_action( 'admin_print_scripts-' . $slidepage, 'shiword_slider_page_script' );
	}
}

if ( !function_exists( 'shiword_slider_page_script' ) ) {
	function shiword_slider_page_script() {
		global $shiword_version;
		wp_enqueue_script( 'sw-slider-script', get_template_directory_uri() . '/js/admin-slider.dev.js', array( 'jquery' ), $shiword_version, true ); //shiword js
	}
}

if ( !function_exists( 'shiword_slider_page_style' ) ) {
	function shiword_slider_page_style() {
		//add custom stylesheet
		wp_enqueue_style( 'sw-slider-style', get_template_directory_uri() . '/css/admin-slider.css', '', false, 'screen' );
	}
}

// display a slideshow for the selected posts
if ( !function_exists( 'shiword_slider' ) ) {
	function shiword_slider() {
		global $post, $shiword_opt, $shiword_is_printpreview;
		
		if ( $shiword_is_printpreview ) return; // no slider in print preview
		
		$posts_list = get_option( 'shiword_slideshow' ); //get the selected posts list
		if ( !isset( $posts_list ) || empty( $posts_list ) ) return; // if no post is selected, exit
		$posts_string = 'include=' . implode( "," , $posts_list ) . '&post_type=any'; // generate the 'include' string for posts
		$ss_posts = get_posts( $posts_string ); // get all the selected posts
		?>
		<div id="sw_slider-wrap">
			<div id="sw_sticky_slider">
				<?php foreach( $ss_posts as $post ) {
					setup_postdata( $post );
					$post_title = get_the_title( $post->ID );
					$post_author = isset( $shiword_opt['shiword_sticky_author'] ) && $shiword_opt['shiword_sticky_author'] == 0? '' : '<span class="sw-slider-auth">' . sprintf( __( 'by %s', 'shiword' ), get_the_author() ) . '</span>';
				?>
					<div class="sss_item">
						<div class="sss_inner_item">
							<a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo $post_title; ?>">
								<?php echo shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft' ); ?>
							</a>
							<div style="padding-left: 130px;">
								<h2 class="storytitle"><a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a></h2> <?php echo $post_author; ?>
								<div class="storycontent">
									<?php the_excerpt(); ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php if ( count($ss_posts) > 1 ) { ?>
				<div class="sw_slider-skip toright"> </div>
				<div class="sw_slider-skip toleft"> </div>
			<?php } ?>
		</div>
		<script type='text/javascript'>
		// <![CDATA[
		jQuery(document).ready(function($){
			$('#sw_sticky_slider').sw_sticky_slider({
				speed : <?php echo isset( $shiword_opt['shiword_sticky_speed'] )? $shiword_opt['shiword_sticky_speed'] : '2500';?>,
				pause : <?php echo isset( $shiword_opt['shiword_sticky_pause'] )? $shiword_opt['shiword_sticky_pause'] : '2000';?>
			})
		})
		// ]]>
		</script>
		<?php 
	}
}

if ( !function_exists( 'shiword_slider_register_settings' ) ) {
	function shiword_slider_register_settings() {
		//register slideshow settings
		register_setting( 'shiw_slideshow_group', 'shiword_slideshow', 'shiword_slider_sanitize'  );
	}
}

if ( !function_exists( 'shiword_slider_sanitize' ) ) {
	function shiword_slider_sanitize( $input ){
		if ( !empty($input) ) {
			//check for numeric value
			foreach ( $input as $key => $val ) {
				if ( is_numeric( $val ) ) {
					$input[$key] = $val;
				} else {
					 unset( $input[$key] );
				}
			}
		}
		return $input;
	}
}

// the slideshow admin panel - here you can select posts to be included in slideshow
if ( !function_exists( 'shiword_slider_edit' ) ) {
	function shiword_slider_edit() {
		global $shiword_opt, $shiword_current_theme;
		$shiword_options = get_option( 'shiword_slideshow' );
		//return options save message
		if ( !$shiword_opt['shiword_sticky'] ) {
			echo '<div id="message" class="updated"><p>'. __( '<strong>The slideshow is disabled!</strong> Enable it in theme options', 'shiword' ) . '</p></div>';
		}
		if ( isset( $_REQUEST['settings-updated'] ) ) {
			echo '<div id="message" class="updated"><p><strong>' . __( 'Options saved.', 'shiword' ) . '</strong></p></div>';
		}
		?>
		<div class="wrap">
			<div class="icon32" id="sw-icon"><br></div>
			<h2><?php echo $shiword_current_theme . ' - ' . __( 'Slideshow', 'shiword' ); ?></h2>
			<div style="margin-top: 20px;">
				<?php _e( 'Select posts or pages to be added to the slideshow box.<br />Items will be ordered as displayed here.', 'shiword' ); ?>
			</div>
			<div>
				<form method="post" action="options.php">
					<?php settings_fields( 'shiw_slideshow_group' ); ?>

					<div id="tabs-container">
						<ul id="selector" class="sw-slidepage-type-list">
							<li id="shiwordSlide-posts-li">
								<a href="#shiwordSlide-posts"><span class="wp-menu-image" style="background-image: url('<?php echo get_admin_url() . 'images/menu.png' ?>')"> </span><?php _e( 'Posts', 'shiword' ); ?></a>
							</li>
							<li id="shiwordSlide-pages-li">
								<a href="#shiwordSlide-pages"><span class="wp-menu-image" style="background-image: url('<?php echo get_admin_url() . 'images/menu.png' ?>')"> </span><?php _e( 'Pages', 'shiword' ); ?></a>
							</li>
						</ul>
						<div class="clear"></div>

						<?php $lastposts = get_posts( 'post_type=post&numberposts=-1&orderby=date' ); ?>

						<div id="shiwordSlide-posts" class="sw-slidepage-type">
							<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Posts','shiword' ); ?></h2>
							<table cellspacing="0" class="widefat post fixed">
								<thead>
									<tr>
										<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
										<th class="manage-column column-title" id="title" scope="col"><?php _e( 'Title', 'shiword' ); ?></th>
										<th class="manage-column column-categories" id="categories" scope="col"><?php _e( 'Categories', 'shiword' ); ?></th>
										<th class="manage-column column-date" id="date" scope="col"><?php _e( 'Date', 'shiword' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach( $lastposts as $post ) {
										$post_title = esc_html( $post->post_title );
										if ( $post_title == "" ) {
											$post_title = __( '(no title)', 'shiword' );
										}
										if ( !isset( $shiword_options[$post->ID] ) ) $shiword_options[$post->ID] = 0;
										?>
										<tr class="sw_post_row">
											<th class="check-column" scope="row">
												<input name="shiword_slideshow[<?php echo $post->ID; ?>]" value="<?php echo $post->ID; ?>" type="checkbox" class="" <?php checked( $post->ID , $shiword_options[$post->ID] ); ?> />
											</th>
											<td class="post-title column-title">
												<?php echo shiword_get_the_thumb( $post->ID, 40, 40, 'slide-thumb' ); ?>
												<a class="row-title" href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a>
											</td>
											<td class="categories column-categories">
												<?php
												foreach( ( get_the_category( $post->ID ) ) as $post_cat ) {
													echo $post_cat->name . ', ';
												}
												?>
											</td>
											<td class="date column-date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $post->post_date_gmt ) );  ?></td>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
										<th class="manage-column column-title" id="title" scope="col"><?php _e( 'Title', 'shiword' ); ?></th>
										<th class="manage-column column-categories" id="categories" scope="col"><?php _e( 'Categories', 'shiword' ); ?></th>
										<th class="manage-column column-date" id="date" scope="col"><?php _e( 'Date', 'shiword' ); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>

						<?php $lastpages = get_posts( 'post_type=page&numberposts=-1&orderby=menu_order' ); ?>

						<div id="shiwordSlide-pages" class="sw-slidepage-type">
							<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Pages','shiword' ); ?></h2>
							<table cellspacing="0" class="widefat post fixed">
								<thead>
									<tr>
										<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
										<th class="manage-column column-title" id="title" scope="col"><?php _e( 'Title', 'shiword' ); ?></th>
										<th class="manage-column column-date" id="date" scope="col"><?php _e( 'Date', 'shiword' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach( $lastpages as $page ) {
										$page_title = esc_html( $page->post_title );
										if ( $page_title == "" ) {
											$page_title = __( '(no title)', 'shiword' );
										}
										if ( !isset( $shiword_options[$page->ID] ) ) $shiword_options[$page->ID] = 0;
										?>
										<tr>
											<th class="check-column" scope="row">
												<input name="shiword_slideshow[<?php echo $page->ID; ?>]" value="<?php echo $page->ID; ?>" type="checkbox" class="" <?php checked( $page->ID , $shiword_options[$page->ID] ); ?> />
											</th>
											<td class="post-title column-title">
												<a class="row-title" href="<?php echo get_permalink( $page->ID ); ?>" title="<?php echo $page_title; ?>"><?php echo $page_title; ?></a>
											</td>
											<td class="date column-date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $page->post_date_gmt ) );  ?></td>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
										<th class="manage-column column-title" id="title" scope="col"><?php _e( 'Title', 'shiword' ); ?></th>
										<th class="manage-column column-date" id="date" scope="col"><?php _e( 'Date', 'shiword' ); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>

						<div class="clear"></div>
					</div>
					<div id="shiwordSlide-bottom_ref" style="clear: both; height: 1px;"> </div>
					<p style="float:left; clear: both;">
						<input class="button-primary" type="submit" name="Submit" value="<?php _e( 'Save Changes', 'shiword' ); ?>" />
						<a style="font-size: 10px; text-decoration: none; margin-left: 10px; cursor: pointer;" href="<?php echo get_admin_url() . 'themes.php?page=tb_shiword_slideshow'; ?>" target="_self"><?php _e( 'Undo Changes' , 'shiword' ); ?></a>
					</p>
				</form>
			</div>
		</div>

		<?php
	}
}


?>
<?php
/**
 * Shiword functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Shiword
 * @since 1.0
 */


/* Custom actions - WP hooks */

add_action( 'after_setup_theme'						, 'shiword_setup' );
add_action( 'wp_enqueue_scripts'					, 'shiword_stylesheet' );
add_action( 'wp_enqueue_scripts'					, 'shiword_scripts' );
add_action( 'template_redirect'						, 'shiword_allcat' );
add_action( 'wp_head'								, 'shiword_ie6_style' );
add_action( 'wp_print_styles'						, 'shiword_deregister_styles', 100 );
add_action( 'admin_bar_menu'						, 'shiword_admin_bar_plus', 999 );
add_action( 'comment_form_before'					, 'shiword_enqueue_comments_reply' );
add_action( 'wp_head'								, 'shiword_microdata' );


/* Custom actions - theme hooks */

add_action( 'shiword_hook_body_top'					, 'shiword_detect_js' );
add_action( 'shiword_hook_like_it'					, 'shiword_like_it' );
add_action( 'shiword_hook_header_after'				, 'shiword_main_menu', 11 );
add_action( 'shiword_hook_comments_list_before'		, 'shiword_navigate_comments' );
add_action( 'shiword_hook_comments_list_after'		, 'shiword_navigate_comments' );
add_action( 'shiword_hook_comments_list_after'		, 'shiword_list_pings' );
add_action( 'shiword_hook_attachment_before'		, 'shiword_navigate_images' );


/* Custom filters - WP hooks */

add_filter( 'embed_oembed_html'						, 'shiword_wmode_transparent', 10, 3);
add_filter( 'excerpt_length'						, 'shiword_excerpt_length' );
add_filter( 'excerpt_mblength'						, 'shiword_excerpt_length' );
add_filter( 'get_comment_author_link'				, 'shiword_add_quoted_on' );
add_filter( 'img_caption_shortcode'					, 'shiword_img_caption_shortcode', 10, 3 );
add_filter( 'get_comments_number'					, 'shiword_comment_count', 0);
add_filter( 'the_title'								, 'shiword_title_tags_filter', 10, 2 );
add_filter( 'excerpt_more'							, 'shiword_new_excerpt_more' );
add_filter( 'the_content_more_link'					, 'shiword_more_link', 10, 2 );
add_filter( 'get_search_form'						, 'shiword_search_form' );
add_filter( 'post_gallery'							, 'shiword_gallery_shortcode', 10, 2 );
add_filter( 'use_default_gallery_style'				, '__return_false' );
add_filter( 'wp_title'								, 'shiword_filter_wp_title', 10, 2 );
add_filter( 'body_class'							, 'shiword_filter_body_class' );
add_filter( 'comment_form_default_fields'			, 'shiword_comments_form_fields' );
add_filter( 'comment_form_defaults'					, 'shiword_comment_form_defaults' );
add_filter( 'the_content'							, 'shiword_quote_content' );
add_filter( 'page_css_class'						, 'shiword_add_parent_class', 10, 4 );
add_filter( 'wp_nav_menu_objects'					, 'shiword_add_menu_parent_class' );
add_filter( 'wp_list_categories'					, 'shiword_wrap_categories_count' );
add_filter( 'wp_nav_menu_items'						, 'shiword_add_home_link', 10, 2 );


/* load options */

$shiword_opt = get_option( 'shiword_options' );


/* theme infos */

function shiword_get_info( $field ) {
	static $infos;

	if ( !isset( $infos ) ) {
		$infos['theme'] = wp_get_theme( 'shiword' );
		$infos['current_theme'] = wp_get_theme();
		$infos['version'] = $infos['theme']? $infos['theme']['Version'] : '';
	}

	return $infos[$field];
}


/* load modules (accordingly to http://justintadlock.com/archives/2010/11/17/how-to-load-files-within-wordpress-themes) */

require_once( 'lib/options.php' ); // load theme default options

require_once( 'lib/widgets.php' ); // load custom widgets module

$shiword_is_mobile = false;
require_once( 'mobile/core-mobile.php' ); // load mobile functions

require_once( 'lib/custom-device-color.php' ); // load custom colors module

require_once( 'lib/my-custom-background.php' ); // load custom background module

require_once( 'lib/admin.php' ); // load admin stuff

require_once( 'lib/slider.php' ); // load slider stuff

require_once( 'lib/hooks.php' ); // load custom hooks

require_once( 'lib/breadcrumb.php' ); // load the breadcrumb module

require_once( 'quickbar.php' ); // load quickbar functions

require_once( 'lib/plugins.php' ); // plugins support

if ( shiword_get_opt( 'shiword_audio_player' ) ) require_once( 'lib/audio-player.php' ); // load the audio player module


/* conditional tags */

function shiword_is_mobile() { // mobile
	global $shiword_is_mobile;
	return $shiword_is_mobile;
}

function shiword_is_printpreview() { //print preview
	static $is_printpreview;
	if ( !isset( $is_printpreview ) ) {
		$is_printpreview = isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ? true : false;
	}
	return $is_printpreview;
}

function shiword_is_allcat() { //is "all category" page
	static $is_allcat;
	if ( !isset( $is_allcat ) ) {
		$is_allcat = isset( $_GET['allcat'] ) && md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ? true : false;
	}
	return $is_allcat;
}


// Set the content width based on the theme's design
if ( ! isset( $content_width ) )
	$content_width = shiword_get_opt( 'shiword_frame_width' ) - 290;


// skip every sidebar if in print preview
if ( !function_exists( 'shiword_get_sidebar' ) ) {
	function shiword_get_sidebar( $name = 'primary' ) {

		if ( $name == 'primary' && shiword_get_layout() == 'wide' ) return;

		if ( apply_filters( 'shiword_skip_' . $name . '_widgets_area', false ) ) return;

		get_sidebar( $name );

	}
}


// Add stylesheets to page
function shiword_stylesheet(){

	if ( is_admin() ) return;
	if ( shiword_is_mobile() ) return;

	//shows print preview / normal view
	if ( shiword_is_printpreview() ) { //print preview
		wp_enqueue_style( 'shiword-print-style-preview', get_template_directory_uri() . '/css/print.css', false, shiword_get_info( 'version' ), 'screen' );
		wp_enqueue_style( 'shiword-general-style-preview', get_template_directory_uri() . '/css/print_preview.css', false, shiword_get_info( 'version' ), 'screen' );
	} else { //normal view
		//thickbox style
		if ( shiword_get_opt( 'shiword_thickbox' ) ) wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'shiword-general-style', get_stylesheet_uri(), false, shiword_get_info( 'version' ), 'screen' );
	}
	//google font
	if ( shiword_get_opt( 'shiword_google_font_family' ) ) wp_enqueue_style( 'shiword-google-fonts', '//fonts.googleapis.com/css?family=' . urlencode( shiword_get_opt( 'shiword_google_font_family' ) ) );
	//print style
	wp_enqueue_style( 'shiword-print-style', get_template_directory_uri() . '/css/print.css', false, shiword_get_info( 'version' ), 'print' );

}


function shiword_ie6_style() {

?>
	<!--[if lte IE 6]><link media="screen" rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/css/ie6.css' ); ?>" type="text/css" /><![endif]-->
<?php

}


// deregister style for WP-Pagenavi plugin (if installed)
function shiword_deregister_styles() {

	wp_deregister_style( 'wp-pagenavi' );

}


// get js modules
function shiword_get_js_modules() {

	$modules[] = 'widgets_style';

	if ( shiword_get_opt( 'shiword_basic_animation_main_menu' ) )			$modules[] = 'main_menu';
	if ( shiword_get_opt( 'shiword_basic_animation_navigation_buttons' ) )	$modules[] = 'navigation_buttons';
	if ( shiword_get_opt( 'shiword_basic_animation_quickbar_panels' ) )		$modules[] = 'quickbar_panels';
	if ( shiword_get_opt( 'shiword_basic_animation_entry_meta' ) )			$modules[] = 'entry_meta';
	if ( shiword_get_opt( 'shiword_basic_animation_smooth_scroll' ) )		$modules[] = 'smooth_scroll';
	if ( shiword_get_opt( 'shiword_basic_animation_resize_video' ) )		$modules[] = 'resize_video';

	if ( shiword_get_opt( 'shiword_tinynav' ) )								$modules[] = 'tinynav';
	if ( shiword_get_opt( 'shiword_sticky' ) )								$modules[] = 'slider';
	if ( shiword_get_opt( 'shiword_qbar_minilogin' ) )						$modules[] = 'minilogin';
	if ( shiword_get_opt( 'shiword_thickbox' ) )							$modules[] = 'thickbox';
	if ( shiword_get_opt( 'shiword_quotethis' ) && is_singular() )			$modules[] = 'quote_this';

	$modules = implode( ',', $modules);

	return  apply_filters( 'shiword_filter_js_modules', $modules );

}


// add scripts
if ( !function_exists( 'shiword_scripts' ) ) {
	function shiword_scripts(){

		if ( shiword_is_mobile() || is_admin() || shiword_is_printpreview() || ! shiword_get_opt( 'shiword_jsani' ) ) return;

		//thickbox script
		if ( shiword_get_opt( 'shiword_thickbox' ) ) wp_enqueue_script( 'thickbox' );

		//tinynav script
		if ( shiword_get_opt( 'shiword_tinynav' ) ) wp_enqueue_script( 'shiword-tinynav', get_template_directory_uri().'/js/tinynav/tinynav.min.js', array( 'jquery' ), shiword_get_info( 'version' ), true );

		wp_enqueue_script( 'shiword-script', get_template_directory_uri().'/js/animations.min.js', array( 'jquery', 'hoverIntent' ), shiword_get_info( 'version' ), true ); //shiword js
		$data = array(
			'script_modules'		=> shiword_get_js_modules(),
			'slider_speed'			=> shiword_get_opt( 'shiword_sticky_speed' ) ? shiword_get_opt( 'shiword_sticky_speed' ) : '2500',
			'slider_pause'			=> shiword_get_opt( 'shiword_sticky_pause' ) ? shiword_get_opt( 'shiword_sticky_pause' ) : '2000',
			'post_expander_wait'	=> esc_js( __( 'Post loading, please wait...', 'shiword' ) ),
			'quote_link_text'		=> esc_js ( __( 'Quote', 'shiword' ) ),
			'quote_link_info'		=> esc_attr( __( 'Add selected text as a quote', 'shiword' ) ),
			'quote_link_alert'		=> esc_js( __( 'Nothing to quote. First of all you should select some text...', 'shiword' ) )
		);
		wp_localize_script( 'shiword-script', 'shiword_l10n', $data );

	}
}


// detect js
function shiword_detect_js(){

?>
	<script type="text/javascript">
		/* <![CDATA[ */
		(function(){
			var c = document.body.className;
			c = c.replace(/sw-no-js/, 'sw-js');
			document.body.className = c;
		})();
		/* ]]> */
	</script>
<?php

}


//enqueue the 'comment-reply' script when needed
function shiword_enqueue_comments_reply() {

	if( get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

}


// show all categories list (redirect to allcat.php if allcat=y)
if ( !function_exists( 'shiword_allcat' ) ) {
	function shiword_allcat () {

		if ( shiword_is_allcat() ) {
			locate_template( array( 'allcat.php' ), true, false );
			exit;
		}

	}
}


// print the next/prev post links
if ( !function_exists( 'shiword_navlinks' ) ) {
	function shiword_navlinks( $position = 'top' ) {

		if ( ! shiword_get_opt( 'shiword_navlinks' ) ) return;

		$sep = ( get_next_post() && get_previous_post() ) ? ' - ' : '';

?>
	<div class="sw-navlinks-<?php echo $position; ?>">
		<?php previous_post_link( '<span class="prev">&laquo; %link</span>' ); ?>
		<?php echo $sep; ?>
		<?php next_post_link( '<span class="next">%link &raquo;</span>' ); ?>
		<div class="fixfloat"> </div>
	</div>
<?php

	}
}


// print the next/prev posts page links
if ( !function_exists( 'shiword_paged_navi' ) ) {
	function shiword_paged_navi() {
		global $paged, $wp_query;

		if ( ! shiword_get_opt( 'shiword_navlinks' ) ) return;

		if ( !$paged )
			$paged = 1;

		if ( function_exists( 'wp_pagenavi' ) ) {
			$links = wp_pagenavi( array( 'echo' => false ) ); 
		} else {
			$links = sprintf( '<span>' . __( 'page %1$s of %2$s', 'shiword' ) . '</span>', $paged, $wp_query->max_num_pages );
			if ( shiword_get_opt( 'shiword_navlinks' ) )
				$links = get_previous_posts_link( '&laquo;' ) . $links . get_next_posts_link( '&raquo;' );
		} 

?>
	<div class="navigate_comments hide-if-infinite">
		<?php echo apply_filters( 'shiword_filter_paged_navi', $links ); ?>
	</div>
<?php 

	}
}


// comments navigation links
function shiword_navigate_comments() {

	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {

?>
	<div class="navigate_comments">
		<?php paginate_comments_links(); ?>
	</div>
<?php

	}

}


// images navigation links
function shiword_navigate_images() {
	global $post;

	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );

	foreach ( $attachments as $key => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}

	$prev_k = $key - 1;
	$next_k = $key + 1;

	$prev_image = ( isset( $attachments[ $prev_k ] ) ) ? '<a class="size-thumbnail" href="' . esc_url( get_attachment_link( $attachments[ $prev_k ]->ID ) ) . '">&laquo; ' . wp_get_attachment_image( $attachments[ $prev_k ]->ID, array( 50, 50 ) ) . '</a>' : '';

	$next_image = ( isset( $attachments[ $next_k ] ) ) ? '<a class="size-thumbnail" href="' . esc_url( get_attachment_link( $attachments[ $next_k ]->ID ) ) . '">' . wp_get_attachment_image( $attachments[ $next_k ]->ID, array( 50, 50 ) ) . ' &raquo;</a>' : '';

?>
	<div class="img-navi">
		<?php echo $prev_image; ?>
		<span class="img-navi-curimg"><?php echo wp_get_attachment_image( $post->ID, array( 50, 50 ) ); ?></span>
		<?php echo $next_image; ?>
	</div>
<?php

}


// pings list
function shiword_list_pings() {
	global $post;

	$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $post->ID ) );

	if ( ! empty( $comments_by_type['pings'] ) ) {

?>
	<ol class="commentlist pings">
		<?php wp_list_comments( 'type=pings' ); ?>
	</ol>
<?php

	}

}


// page hierarchy
if ( !function_exists( 'shiword_multipages' ) ) {
	function shiword_multipages( $r_pos ){
		global $post;

		$args = array(
			'post_type'		=> 'page',
			'post_parent'	=> $post->ID,
			'order'			=> 'ASC',
			'orderby'		=> 'menu_order',
			'numberposts'	=> 0,
			);
		$childrens = get_posts( $args ); // retrieve the child pages
		$the_parent_page = $post->post_parent; // retrieve the parent page
		$has_herarchy = false;

		if ( ( $childrens ) || ( $the_parent_page ) ){ // add the hierarchy metafield ?>
			<div class="metafield">
				<div class="metafield_trigger mft_hier" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
				<div class="metafield_content">
					<?php
					if ( $the_parent_page ) {
						$the_parent_link = '<a href="' . esc_url( get_permalink( $the_parent_page ) ) . '" title="' . esc_attr( strip_tags( get_the_title( $the_parent_page ) ) ) . '">' . get_the_title( $the_parent_page ) . '</a>';
						echo __( 'Parent page', 'shiword' ) . ': ' . $the_parent_link ; // echoes the parent
					}
					if ( ( $childrens ) && ( $the_parent_page ) ) { echo ' - '; } // if parent & child, echoes the separator
					if ( $childrens ) {
						$the_child_list = '';
						foreach ( $childrens as $children ) {
							$the_child_list[] = '<a href="' . esc_url( get_permalink( $children ) ) . '" title="' . esc_attr( strip_tags( get_the_title( $children ) ) ) . '">' . get_the_title( $children ) . '</a>';
						}
						$the_child_list = implode( ', ' , $the_child_list );
						echo __( 'Child pages', 'shiword' ) . ': ' . $the_child_list; // echoes the childs
					}
					?>
				</div>
			</div>
			<?php
			$has_herarchy = true;
		}

		return $has_herarchy;

	}
}


//add "like" badges to post/page
function shiword_like_it(){

	$text = apply_filters( 'shiword_like_it', '' );
	if ( $text )
		echo '<div class="sw-I-like-it">' . $text . '<div class="fixfloat"> </div></div>';

}


// print extra info for posts/pages
if ( !function_exists( 'shiword_extrainfo' ) ) {
	function shiword_extrainfo( $args = '' ) {

		$defaults = array(
			'auth'		=> 1,
			'date'		=> 1,
			'comms'		=> 1,
			'tags'		=> 1,
			'cats'		=> 1,
			'hiera'		=> 1,
			'in_index'	=> 1,
		);

		$args = wp_parse_args( $args, $defaults );

		//xinfos disabled when...
		if ( ! shiword_get_opt( 'shiword_xinfos_global' ) ) return; //xinfos globally disabled
		if ( is_front_page() && ( get_option( 'show_on_front' ) == 'page' ) ) return; // is front page
		if ( ! is_singular() && ! shiword_get_opt( 'shiword_xinfos' ) ) return; // !'in posts index' + is index
		if ( ! is_singular() && ! $args['in_index'] ) return; // !'in_index' + is index

		$r_pos = 10;

		// animated xinfos
		if ( ! shiword_get_opt( 'shiword_xinfos_static' ) ) {
		?>
		<div class="meta_container">
			<div class="meta top_meta ani_meta">
			<?php
			// author
			if ( $args['auth'] && ( shiword_get_opt( 'shiword_byauth' ) ) ) { ?>
				<?php $post_auth = ( $args['auth'] === 1 ) ? '<a class="author vcard" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts by %s', 'shiword' ), get_the_author() ) ) . '">' . get_the_author() . '</a>' : $args['auth']; ?>
				<div class="metafield_trigger" style="left: 10px;"><?php printf( __( 'by %s', 'shiword' ), $post_auth ); ?></div>
			<?php
			}
			// categories
			if ( $args['cats'] && ( shiword_get_opt( 'shiword_xinfos_cat' ) ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_cat" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php echo __( 'Categories', 'shiword' ) . ': '; ?>
						<?php the_category( ', ' ); ?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			// tags
			if ( $args['tags'] && ( shiword_get_opt( 'shiword_xinfos_tag' ) ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_tag" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php echo __( 'Tags', 'shiword' ) . ': '; ?>
						<?php if ( !get_the_tags() ) { _e( 'No Tags', 'shiword' ); } else { the_tags( '', ', ', '' ); } ?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			// comments
			$page_cd_nc = ( is_page() && !comments_open() && !have_comments() ); //true if page with comments disabled and no comments
			if ( $args['comms'] && ( shiword_get_opt( 'shiword_xinfos_comm' ) ) && !$page_cd_nc ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_comm" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php echo __( 'Comments', 'shiword' ) . ': '; ?>
						<?php comments_popup_link( __( 'No Comments', 'shiword' ), __( '1 Comment', 'shiword', 'shiword' ), __( '% Comments', 'shiword' ) ); // number of comments?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			// date
			if ( $args['date'] && ( shiword_get_opt( 'shiword_xinfos_date' ) ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_date" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php
						printf( __( 'Published on: %1$s', 'shiword' ), '<span class="published">' . get_the_time( get_option( 'date_format' ) ) . '</span>' );
						?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			// hierarchy
			if ( $args['hiera'] && is_page() && !is_front_page() ) {
			?>
			<?php if ( shiword_multipages( $r_pos ) ) { $r_pos = $r_pos + 30; } ?>
			<?php
			}
			?>
				<div class="metafield_trigger edit_link" style="right: <?php echo $r_pos; ?>px;"><?php edit_post_link( __( 'Edit', 'shiword' ), '' ); ?></div>
			</div>
		</div>
		<?php
		} else { //static xinfos ?>
			<div class="meta">
				<?php if ( $args['auth'] && shiword_get_opt( 'shiword_byauth' ) ) { printf( __( 'by %s', 'shiword' ), '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( sprintf( 'View all posts by %s', get_the_author() ) ) . '">' . get_the_author() . '</a>' ); echo '<br />'; }; ?>
				<?php if ( $args['date'] && shiword_get_opt( 'shiword_xinfos_date' ) ) { printf( __( 'Published on: %1$s', 'shiword' ), get_the_time( get_option( 'date_format' ) ) ) ; echo '<br />'; }?>
				<?php if ( $args['comms'] && shiword_get_opt( 'shiword_xinfos_comm' ) ) { echo __( 'Comments', 'shiword' ) . ': '; comments_popup_link( __( 'No Comments', 'shiword' ), __( '1 Comment', 'shiword' ), __( '% Comments', 'shiword' ) ); echo '<br />'; } ?>
				<?php if ( $args['tags'] && shiword_get_opt( 'shiword_xinfos_tag' ) ) { echo __( 'Tags', 'shiword' ) . ': '; if ( !get_the_tags() ) { _e( 'No Tags', 'shiword' ); } else { the_tags( '', ', ', '' ); }; echo '<br />';  } ?>
				<?php if ( $args['cats'] && shiword_get_opt( 'shiword_xinfos_cat' ) ) { echo __( 'Categories', 'shiword' ) . ': '; the_category( ', ' ); echo '<br />'; } ?>
				<?php edit_post_link( __( 'Edit', 'shiword' ) ); ?>
			</div>
		<?php
		}

	}
}


// Search reminder
function shiword_search_reminder() {

	$text = '';

	if ( is_archive() ) {

		$term = get_queried_object();
		$title = '';
		$type = '';
		if ( is_category() || is_tag() || is_tax() ) {
			if ( is_category() )	$type = __( 'Category', 'shiword' );
			elseif ( is_tag() )		$type = __( 'Tag', 'shiword' );
			elseif ( is_tax() )		$type = __( 'Taxonomy', 'shiword' );
			$title = $term->name;
		} elseif ( is_date() ) {
			$type = __( 'Date', 'shiword' );
			if ( is_day() ) {
				$title = get_the_date();
			} else if ( is_month() ) {
				$title = single_month_title( ' ', false );
			} else if ( is_year() ) {
				$title = get_query_var( 'year' );
			}
		} elseif ( is_author() ) {
			$type = __( 'Author', 'shiword' );
			$title = $term->display_name;
		}

		$text = sprintf( __( '%s archive', 'shiword' ), get_bloginfo( 'name' ) ) . '<br>' . $type . ' : <span class="sw-search-term">' . $title . '</span>';

	} elseif ( is_search() ) {

		$text = sprintf( __( 'Search results for &#8220;%s&#8221;', 'shiword' ), '<span class="sw-search-term">' . esc_html( get_search_query() ) . '</span>' );

	}

	if ( $text ) {

?>
	<div class="meta sw-search-reminder">
		<p><?php echo $text; ?></p>
	</div>
<?php

	}

	if ( shiword_get_opt( 'shiword_cat_description' ) && is_category() && category_description() ) { 

?>
	<div class="meta">
		<p><?php echo category_description(); ?></p>
	</div>
<?php

	}

	if ( is_author() ) shiword_post_details( array( 'date' => 0, 'tags' => 0, 'categories' => 0 ) );

}


// add a fix for embed videos
function shiword_wmode_transparent($html, $url, $attr) {

	if ( strpos( $html, '<embed ' ) !== false ) {

		$html = str_replace( '</param><embed', '</param><param name="wmode" value="transparent"></param><embed', $html);
		$html = str_replace( '<embed ', '<embed wmode="transparent" ', $html);

	} elseif ( strpos ( $html, 'feature=oembed' ) !== false ) {

		$html = str_replace( 'feature=oembed', 'feature=oembed&wmode=transparent', $html );

	}

	return $html;

}


// Get first image of a post
if ( !function_exists( 'shiword_get_first_image' ) ) {
	function shiword_get_first_image() {
		global $post;

		$first_info = array( 'img' => '', 'title' => '', 'src' => '',);
		//search the images in post content
		preg_match_all( '/<img[^>]+>/i', $post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_info['img'] = $result[0][0];
			$first_img = $result [0][0];
			//get the title (if any)
			preg_match_all( '/(title)=("[^"]*")/i', $first_img, $img_title );
			if ( isset( $img_title[2][0] ) ){
				$first_info['title'] = str_replace( '"', '', $img_title[2][0] );
			}
			//get the path
			preg_match_all( '/(src)=("[^"]*")/i', $first_img, $img_src );
			if ( isset( $img_src[2][0] ) ){
				$first_info['src'] = str_replace( '"', '', $img_src[2][0] );
			}
			return $first_info;
		} else {
			return false;
		}

	}
}


// Get first link of a post
if ( !function_exists( 'shiword_get_first_link' ) ) {
	function shiword_get_first_link() {
		global $post;

		$first_info = array( 'anchor' => '', 'title' => '', 'href' => '', 'text' => '' );
		//search the link in post content
		preg_match_all( "/<a\b[^>]*>(.*?)<\/a>/i", $post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_info['anchor'] = $result[0][0];
			$first_info['text'] = isset( $result[1][0] ) ? $result[1][0] : '';
			//get the title (if any)
			preg_match_all( '/(title)=(["\'][^"]*["\'])/i', $first_info['anchor'], $link_title );
			$first_info['title'] = isset( $link_title[2][0] ) ? str_replace( array( '"', '\'' ), '', $link_title[2][0] ) : '';
			//get the path
			preg_match_all( '/(href)=(["\'][^"]*["\'])/i', $first_info['anchor'], $link_href );
			$first_info['href'] = isset( $link_href[2][0] ) ? str_replace( array( '"', '\'' ), '', $link_href[2][0] ) : '';
			return $first_info;
		} else {
			return false;
		}

	}
}


// Get first blockquote words
if ( !function_exists( 'shiword_get_blockquote' ) ) {
	function shiword_get_blockquote() {
		global $post;

		$first_quote = array( 'quote' => '', 'cite' => '' );
		//search the blockquote in post content
		preg_match_all( '/<blockquote>([\w\W]*?)<\/blockquote>/', $post->post_content, $blockquote );
		//grab the first one
		if ( isset( $blockquote[0][0] ) ){
			$first_quote['quote'] = strip_tags( $blockquote[0][0] );
			$words = explode( " ", $first_quote['quote'], 6 );
			if ( count( $words ) == 6 ) $words[5] = '...';
			$first_quote['quote'] = implode( ' ', $words );
			preg_match_all( '/<cite>([\w\W]*?)<\/cite>/', $blockquote[0][0], $cite );
			$first_quote['cite'] = ( isset( $cite[1][0] ) ) ? $cite[1][0] : '';
			return $first_quote;
		} else {
			return false;
		}

	}
}


// Get first gallery
if ( !function_exists( 'shiword_get_gallery_shortcode' ) ) {
	function shiword_get_gallery_shortcode() {
		global $post;

		$pattern = get_shortcode_regex();

		if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
			&& array_key_exists( 2, $matches )
			&& is_array( $matches[2] )
			&& in_array( 'gallery', $matches[2] ) ) // gallery shortcode is being used
		{
			$key = array_search( 'gallery', $matches[2] );
			$attrs = shortcode_parse_atts( $matches['3'][$key] );
			return $attrs;
		}

	}
}


// run the gallery preview
if ( !function_exists( 'shiword_gallery_preview' ) ) {
	function shiword_gallery_preview() {

		$attrs = shiword_get_gallery_shortcode();
		$attrs['preview'] = true;

		return shiword_gallery_shortcode( '', $attrs );

	}
}


// the gallery preview walker
if ( !function_exists( 'shiword_gallery_preview_walker' ) ) {
	function shiword_gallery_preview_walker( $attachments = '', $id = 0 ) {

		if ( ! $id )
			return false;

		if ( empty( $attachments ) )
			$attachments = get_children( array( 'post_parent' => $id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );

		if ( empty( $attachments ) )
			return false;

		$permalink = esc_url( get_permalink( $id ) );

		$images_count = count( $attachments );
		$first_image = array_shift( $attachments );

		if ( ( shiword_get_opt( 'shiword_xcont' ) == 1 ) || is_archive() || is_search() ) { // compact view

			$first_image_data = wp_get_attachment_image_src( $first_image->ID, 'thumbnail' );
			$first_image_tag = wp_get_attachment_image( $first_image->ID, 'thumbnail' );
			$gallery_thumb_width = esc_attr( min( get_option('thumbnail_size_w'), $first_image_data[1] ) );
			$other_imgs = array();
			$other_imgs_width = 0;

		} else { // normal view

			$first_image_data = wp_get_attachment_image_src( $first_image->ID, 'medium' );
			$first_image_tag = wp_get_attachment_image( $first_image->ID, 'medium' );
			$gallery_thumb_width = esc_attr( min( get_option('medium_size_w'), $first_image_data[1] ) );
			$other_imgs = array_slice( $attachments, 0, 4 );
			$other_imgs_width = floor( get_option('thumbnail_size_w')/2 );

		}

?>
	<div class="gallery gallery-preview">

		<div class="gallery-item img-caption" style="width: <?php echo $gallery_thumb_width; ?>px;">
			<a href="<?php echo $permalink; ?>"><?php echo $first_image_tag; ?></a>
		</div><!-- .gallery-thumb -->

		<?php foreach ($other_imgs as $image) { ?>
			<div class="gallery-item img-caption" style="width: <?php echo $other_imgs_width; ?>px;">
				<a href="<?php echo $permalink; ?>"><?php echo wp_get_attachment_image( $image->ID, array( $other_imgs_width, $other_imgs_width ) ); ?></a>
			</div>
		<?php } ?>

		<p class="info">
			<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $images_count, 'shiword' ),
				'href="' . $permalink . '" title="' . __( 'View gallery', 'shiword' ) . '" rel="bookmark"',
				number_format_i18n( $images_count )
				); ?></em>
		</p>

	</div>
<?php

		return true;

	}
}


// get the post thumbnail or (if not set) the format related icon
function shiword_get_the_thumb( $args = '' ) {
	global $post;

	$defaults = array(
		'id'			=> $post->ID,
		'width'			=> shiword_get_opt( 'shiword_pthumb_size' ),
		'height'		=> shiword_get_opt( 'shiword_pthumb_size' ),
		'class'			=> '',
		'linked'		=> 0,
		'onlyformat'	=> 0,
	);

	$args = wp_parse_args( $args, $defaults );

	if ( shiword_get_opt( 'shiword_pthumb_qr' ) && ! $args['onlyformat'] )
		$output = '<img class="feaured-image qrcode ' . $args['class'] . '" src="http://chart.apis.google.com/chart?cht=qr&chs=' . $args['width'] . 'x' . $args['height'] . '&chl=' . urlencode( home_url() . '/?p=' . $args['id'] ) . '&chld=L|0" alt="thumbnail" />';
	elseif ( has_post_thumbnail( $args['id'] ) && ! $args['onlyformat'] ) {
		$output = get_the_post_thumbnail( $args['id'], array( $args['width'], $args['height'] ), array( 'class' => $args['class'] ) );
	} else {
		if ( shiword_is_post_format_available( $args['id'] ) ) {
			$format = get_post_format( $args['id'] );
		} else {
			$format = 'thumb';
		}
		$output = '<img class="' . esc_attr( $args['class'] ) . ' wp-post-image" width="' . esc_attr( $args['width'] ) . '" height="' . esc_attr( $args['height'] ) . '" alt="thumb" src="' . esc_url( get_template_directory_uri() . '/images/thumbs/' . $format . '.png' ) . '" />';
	}

	if ( $args['linked'] )
		$output = '<a href="' . esc_url( get_permalink( $args['id'] ) ) . '" rel="bookmark">' . $output . '</a>';

	return apply_filters( 'shiword_filter_get_the_thumb', $output );

}


// display the thumb
if ( !function_exists( 'shiword_thumb' ) ) {
	function shiword_thumb() {

		if( shiword_get_opt( 'shiword_pthumb' ) )
			echo shiword_get_the_thumb( array( 'class' => 'alignleft', 'linked' => 1 ) );

	}
}


// display the post title with the featured image
if ( !function_exists( 'shiword_post_title' ) ) {
	function shiword_post_title( $args = '' ) {
		global $post;

		$defaults = array(
			'alternative'	=> '',
			'featured'		=> 0,
			'href'			=> get_permalink(),
			'target'		=> '',
			'title'			=> the_title_attribute( array( 'echo' => 0 ) ),
			'extra'			=> '',
		);

		$args = wp_parse_args( $args, $defaults );

		$post_title = $args['alternative'] ? $args['alternative'] : get_the_title();

		$link_target = $args['target'] ? ' target="'.$args['target'].'"' : '';

		$has_format = shiword_is_post_format_available( $post->ID );
		$has_featured_image = $args['featured'] && shiword_get_opt( 'shiword_supadupa_title' ) && has_post_thumbnail( $post->ID ) && ( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( 700, 700 ) ) ) && ( $image[1] >= 700 ) && ( $image[2] >= 200 );

		if ( ! $has_format || is_singular() || ( shiword_get_opt( 'shiword_pthumb' ) && ! has_post_thumbnail( $post->ID ) ) )
			$miniicon = '';
		else
			$miniicon = apply_filters( 'shiword_filter_mini_icon', shiword_get_the_thumb( array( 'width' => 32, 'height' => 32, 'class' => 'alignleft', 'linked' => 1, 'onlyformat' => 1 ) ) );

		if ( $post_title ) $post_title = '<h2 class="storytitle entry-title">' . $miniicon . $args['extra'] . '<a title="' . esc_attr( $args['title'] ) . '" href="' . esc_url( $args['href']) . '"' . esc_attr( $link_target ) . ' rel="bookmark">' . $post_title . '</a></h2>';

		shiword_hook_post_title_before();

		if ( $has_featured_image ) {
			?>
			<div class="storycontent sd-post-title">
				<img src="<?php echo esc_url( $image[0] ); ?>" width="<?php echo esc_attr( $image[1] ); ?>" height="<?php echo esc_attr( $image[2] ); ?>">
				<?php echo $post_title; ?>
			</div>
			<?php
		} else {
			echo $post_title;
		}

		shiword_hook_post_title_after();

	}
}


// print extra info for posts/pages
if ( !function_exists( 'shiword_post_details' ) ) {
	function shiword_post_details( $args = '' ) {
		global $post;

		$defaults = array(
			'author'		=> 1,
			'date'			=> 1,
			'tags'			=> 1,
			'categories'	=> 1,
			'avatar_size'	=> 48,
			'featured'		=> 0,
			'echo'			=> 1,
		);

		$args = wp_parse_args( $args, $defaults );

		$tax_separator = apply_filters( 'shiword_filter_taxomony_separator', ', ' );

		$output = '';

		if ( $args['featured'] &&  has_post_thumbnail( $post->ID ) )
			$output .= '<div class="tb-post-details post-details-thumb">' . get_the_post_thumbnail( $post->ID, 'thumbnail') . '</div>';

		if ( $args['author'] )
			$output .= shiword_author_badge( $post->post_author, $args['avatar_size'] );

		if ( $args['categories'] )
			$output .= '<div class="tb-post-details"><span class="post-details-cats">' . __( 'Categories', 'shiword' ) . ': </span>' . get_the_category_list( $tax_separator ) . '</div>';

		if ( $args['tags'] ) {
			$tags = get_the_tags() ? get_the_tag_list( '</span>', $tax_separator, '' ) : __( 'No Tags', 'shiword' ) . '</span>';
			$output .= '<div class="tb-post-details"><span class="post-details-tags">' . __( 'Tags', 'shiword' ) . ': ' . $tags . '</div>';
		}

		if ( $args['date'] )
			$output .= '<div class="tb-post-details"><span class="post-details-date">' . __( 'Published on', 'shiword' ) . ': </span><a href="' . esc_url( get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ) ) . '">' . get_the_time( get_option( 'date_format' ) ) . '</a></div>';

		if ( ! $args['echo'] )
			return $output;

		echo $output;

	}
}


// get the author badge
function shiword_author_badge( $author = '', $size ) {

	if ( ! $author ) return;

	$name = get_the_author_meta( 'nickname', $author ); // nickname

	$avatar = get_avatar( $author, $size, 'Gravatar Logo', get_the_author_meta( 'user_nicename', $author ) . '-photo' ); // gravatar

	$description = get_the_author_meta( 'description', $author ); // bio

	$author_link = esc_url( get_author_posts_url($author) ); // link to author posts

	$author_net = ''; // author social networks
	foreach ( array( 'twitter' => 'Twitter', 'facebook' => 'Facebook', 'googleplus' => 'Google+' ) as $s_key => $s_name ) {
		if ( get_the_author_meta( $s_key, $author ) ) $author_net .= '<a target="_blank" class="url" title="' . esc_attr( sprintf( __('Follow %s on %s', 'shiword'), $name, $s_name ) ) . '" href="' . esc_url( get_the_author_meta( $s_key, $author ) ) . '"><img alt="' . $s_key . '" class="social-icon" width="24" height="24" src="' . esc_url( get_template_directory_uri() . '/images/follow/' . $s_key . '.png' ) . '" /></a>';
	}

	$output = '<li class="author-avatar">' . $avatar . '</li>';
	$output .= '<li class="author-name"><a class="fn" href="' . $author_link . '" >' . $name . '</a></li>';
	$output .= $description ? '<li class="author-description note">' . $description . '</li>' : '';
	$output .= $author_net ? '<li class="author-social">' . $author_net . '</li>' : '';

	$output = '<div class="tb-post-details tb-author-bio vcard"><ul>' . $output . '</ul></div>';

	return apply_filters( 'shiword_filter_author_badge', $output );

}


// theme credits
if ( !function_exists( 'shiword_theme_credits' ) ) {
	function shiword_theme_credits() {

?>
	&copy; <?php echo date( 'Y' ); ?> <strong><?php bloginfo( 'name' ); ?></strong>

	<?php shiword_hook_change_view(); ?>

	<?php if ( shiword_get_opt( 'shiword_tbcred' ) ) { ?>
		<a href="http://www.twobeers.net/" title="Shiword theme<?php echo ' ' . esc_attr( shiword_get_info( 'version' ) ); ?> by TwoBeers Crew">
			<img alt="twobeers.net" src="<?php echo esc_url( get_template_directory_uri() . '/images/tb_micrologo.png' ); ?>" />
		</a>
		<a href="http://wordpress.org/" title="<?php esc_attr_e( 'Powered by WordPress', 'shiword' ); ?>">
			<img alt="WordPress" src="<?php echo esc_url( get_template_directory_uri() . '/images/wp_micrologo.png' ); ?>" />
		</a>
	<?php } ?>
 <?php

	}
}


// return the classes of main content
if ( !function_exists( 'shiword_content_class' ) ) {
	function shiword_content_class( $class = array() ) {

		if ( ! empty( $class ) ) {
			if ( !is_array( $class ) )
				$class = preg_split( '#[\s,]+#', $class );
		}

		if ( ( is_archive() || is_home() || is_search() || ( is_front_page() && get_option( 'show_on_front' )== 'posts' ) ) && shiword_get_opt( 'shiword_pthumb' ) )
			$class[] = 'sw-has-thumb';

		$class[] = 'posts_' . shiword_get_layout();

		$class = array_map( 'esc_attr', $class );

		$class = apply_filters( 'shiword_content_class', $class );

		echo join( ' ', $class );

	}
}


// return the current layout (wide/narrow)
if ( !function_exists( 'shiword_get_layout' ) ) {
	function shiword_get_layout( $page = '' ) {

		static $layout;

		if ( ! isset( $layout ) ) {

			$layout = 'narrow';

			if (
				( ! shiword_get_opt( 'shiword_rsideb' ) && ! is_singular() ) ||
				( ! shiword_get_opt( 'shiword_rsidebpages' ) && $page == 'page' ) ||
				( ! shiword_get_opt( 'shiword_rsidebattachment' ) && $page == 'attachment' ) ||
				( ! shiword_get_opt( 'shiword_rsidebposts' ) && $page == 'post' ) ||
				( $page == 'one-column' )
			)
				$layout = 'wide';

		}

		return apply_filters( 'shiword_get_layout', $layout );

	}
}


// return the current post format
if ( !function_exists( 'shiword_get_format' ) ) {
	function shiword_get_format( $id = null ) {
		global $post;

		if ( $id == null ) $id = $post->ID;

		if ( post_password_required( $id ) )
			return 'protected';

		$format = get_post_format( $id );

		if ( ! $format )
			return 'standard';

		$format = ( shiword_get_opt( 'shiword_postformat_' . $format ) ) ? $format : 'standard' ;

		return $format;

	}
}


// print the site title and description
if ( !function_exists( 'shiword_site_title' ) ) {
	function shiword_site_title() {

		if ( shiword_get_opt( 'shiword_site_title' ) )
			echo '<h1><a href="' . esc_url( home_url() ) . '/">' . get_bloginfo( 'name' ) . '</a></h1>';

		if ( shiword_get_opt( 'shiword_site_description' ) )
			echo '<div class="description">' . get_bloginfo( 'description' ) . '</div>';

?>
	<div id="rss_imglink" class="minibutton">
		<a href="<?php echo esc_url( apply_filters( 'shiword_filter_rss_service', get_bloginfo( 'rss2_url' ) ) ); ?>" title="<?php esc_attr_e( 'Syndicate this site using RSS 2.0', 'shiword' ); ?>">
			<span class="minib_img">&nbsp;</span>
		</a>
	</div>
<?php

	}
}


//print some microdata tags
function shiword_microdata() {

	if ( ! is_singular() ) return;

	$name = the_title_attribute( array( 'echo' => false ) );

?>
	<meta itemprop="name" content="<?php echo $name; ?>">
<?php

	if( has_post_thumbnail() ) {
		$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id() );
		$image = esc_url( $image_attributes[0] );

?>
	<meta itemprop="image" content="<?php echo $image; ?>">
<?php

	}

}


//the main menu
function shiword_main_menu() {

	if ( shiword_get_opt( 'shiword_hide_primary_menu' ) ) return;

?>
	<div id="sw-pri-menu" class="sw-menu">
		<?php
			wp_nav_menu( array(
				'container'			=> false,
				'menu_id'			=> 'mainmenu',
				'menu_class'		=> 'nav-menu',
				'fallback_cb'		=> 'shiword_pages_menu',
				'theme_location'	=> 'primary'
			) );
		?>
	</div>
<?php

}


// Pages Menu
if ( !function_exists( 'shiword_pages_menu' ) ) {
	function shiword_pages_menu() {

?>
	<ul id="mainmenu" class="nav-menu">

		<?php echo shiword_add_home_link( $items = '', $args = 'theme_location=primary' ); ?>

		<?php wp_list_pages( 'sort_column=menu_order&title_li=' ); // menu-order sorted ?>

	</ul>
<?php

	}
}


//add "Home" link
function shiword_add_home_link( $items = '', $args = null ) {

	$defaults = array(
		'theme_location'	=> 'undefined',
		'before'			=> '',
		'after'				=> '',
		'link_before'		=> '',
		'link_after'		=> '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( ( $args['theme_location'] === 'primary' ) && ( 'posts' == get_option( 'show_on_front' ) ) ) {
		if ( is_front_page() || is_single() )
			$class = ' current_page_item';
		else
			$class = '';

		$homeMenuItem =
				'<li class="menu-item navhome' . $class . '">' .
				$args['before'] .
				'<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr__( 'Home', 'shiword' ) . '">' .
				$args['link_before'] . __( 'Home', 'shiword' ) . $args['link_after'] .
				'</a>' .
				$args['after'] .
				'</li>';

		$items = $homeMenuItem . $items;
	}

	return $items;

}


if ( !function_exists( 'shiword_setup' ) ) {
	function shiword_setup() {

		// Make theme available for translation
		load_theme_textdomain( 'shiword', get_template_directory() . '/languages' );

		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );

		// Used for featured posts if a large-feature doesn't exist.
		add_image_size( 'large-feature', 700, 300 );

		// This theme uses post formats
		if ( shiword_get_opt( 'shiword_postformats' ) ) {
			$supported_formats = array();
			foreach ( array( 'aside', 'gallery', 'audio', 'quote', 'image', 'video', 'link', 'status' ) as $format ) {
				if ( shiword_get_opt( 'shiword_postformat_' . $format ) ) $supported_formats[] = $format;
			}
			if ( ! empty( $supported_formats ) ) add_theme_support( 'post-formats', $supported_formats );
		}

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Add the editor style
		if ( shiword_get_opt( 'shiword_editor_style' ) )
			add_editor_style( 'css/editor-style.css' );

		// Theme uses wp_nav_menu() in two locations
		register_nav_menus( array( 'primary' => __( 'Main Navigation Menu', 'shiword' ), 'secondary' => __( 'Secondary Navigation Menu<br><small>only supports the first level of hierarchy</small>', 'shiword' ) ) );

		shiword_setup_custom_header();

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'green' => array(
				'url'			=> '%s/images/headers/green.jpg',
				'thumbnail_url'	=> '%s/images/headers/green_thumbnail.jpg',
				'description'	=> 'green'
			),
			'black' => array(
				'url'			=> '%s/images/headers/black.jpg',
				'thumbnail_url'	=> '%s/images/headers/black_thumbnail.jpg',
				'description'	=> 'black'
			),
			'brown' => array(
				'url'			=> '%s/images/headers/brown.jpg',
				'thumbnail_url'	=> '%s/images/headers/brown_thumbnail.jpg',
				'description'	=> 'brown'
			),
			'blue' => array(
				'url'			=> '%s/images/headers/blue.jpg',
				'thumbnail_url'	=> '%s/images/headers/blue_thumbnail.jpg',
				'description'	=> 'blue'
			),
			'butterflies' => array(
				'url'			=> '%s/images/headers/butterflies.gif',
				'thumbnail_url'	=> '%s/images/headers/butterflies_thumbnail.jpg',
				'description'	=> 'butterflies'
			),
		) );

	}
}


//the custom header support
if ( !function_exists( 'shiword_setup_custom_header' ) ) {
	function shiword_setup_custom_header() {

		$head_h = shiword_get_opt( 'shiword_head_h' ) ? str_replace( 'px', '', shiword_get_opt( 'shiword_head_h' ) ) : 100;
		$head_w = shiword_get_opt( 'shiword_frame_width' ) ? shiword_get_opt( 'shiword_frame_width' ) : 850;

		$args = array(
			'width'					=> $head_w, // Header image width (in pixels)
			'height'				=> $head_h, // Header image height (in pixels)
			'default-image'			=> get_template_directory_uri() . '/images/headers/green.jpg', // Header image default
			'header-text'			=> false, // Header text display default
			'default-text-color'	=> '404040', // Header text color default
			'wp-head-callback'		=> 'shiword_header_style',
			'admin-head-callback'	=> 'shiword_admin_header_style',
			'flex-height'			=> true
		);

		$args = apply_filters( 'shiword_custom_header_args', $args );

		add_theme_support( 'custom-header', $args );

	}
}


// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'shiword_admin_header_style' ) ) {
	function shiword_admin_header_style() {

		echo '<link rel="stylesheet" type="text/css" href="' . esc_url( get_template_directory_uri() . '/css/admin-custom_header.css' ) . '" />' . "\n";

	}
}


// convert hex color value to rgba
if ( !function_exists( 'shiword_hex2rgba' ) ) {
	function shiword_hex2rgba( $hex, $alpha ) {

		$color = str_replace( '#', '', $hex);
		if ( $color == 'transparent' ) {
			return 'transparent';
		} else {
			$rgba = 'rgba(' . hexdec(substr($color,0,2)) . ',' . hexdec(substr($color,2,2)) . ',' . hexdec(substr($color,4,2)) . ',' . round(intval($alpha) / 100, 3) . ')';
			return $rgba;
		}

	}
}


// get lightness of from rgb color
if ( !function_exists( 'shiword_rgblight' ) ) {
	function shiword_rgblight($hex) {

		$color = str_replace( '#', '', $hex);
		$rgba['r'] = hexdec(substr($color,0,2));
		$rgba['g'] = hexdec(substr($color,2,2));
		$rgba['b'] = hexdec(substr($color,4,2));

		$var_min = min( $rgba['r'], $rgba['g'], $rgba['b'] );	//min. value of rgb
		$var_max = max( $rgba['r'], $rgba['g'], $rgba['b'] );	//max. value of rgb

		$lightness = ( $var_max + $var_min ) / 2;

		return $lightness;

	}
}


// custom header image style - gets included in the site header
if ( !function_exists( 'shiword_header_style' ) ) {
	function shiword_header_style() {
		global $shiword_colors, $shiword_opt;

		if ( shiword_is_mobile() ) return;
		$device_rgba = shiword_hex2rgba( $shiword_colors['device_color'], $shiword_colors['device_opacity']);
		$head_h = get_custom_header()->height;

?>
	<style type="text/css">
		body {
			font-size: <?php echo $shiword_opt['shiword_font_size']; ?>;
			<?php if ( $shiword_opt['shiword_google_font_family'] && $shiword_opt['shiword_google_font_body'] ) { ?>
				font-family: <?php echo $shiword_opt['shiword_google_font_family']; ?>;
			<?php } else { ?>
				font-family: <?php echo $shiword_opt['shiword_font_family']; ?>;
			<?php } ?>
		}
		<?php if ( $shiword_opt['shiword_google_font_family'] && $shiword_opt['shiword_google_font_post_title'] ) { ?>
		h2.storytitle {
			font-family: <?php echo $shiword_opt['shiword_google_font_family']; ?>;
		}
		<?php } ?>
		<?php if ( $shiword_opt['shiword_google_font_family'] && $shiword_opt['shiword_google_font_post_content'] ) { ?>
		.storycontent {
			font-family: <?php echo $shiword_opt['shiword_google_font_family']; ?>;
		}
		<?php } ?>
		#headerimg {
			background: transparent url('<?php header_image(); ?>') right bottom repeat-y;
			min-height: <?php echo $head_h ?>px;
		}
		#sw_body,
		#fixedfoot {
			background: <?php echo $device_rgba; ?> url('<?php echo $shiword_colors['device_image']; ?>') right top repeat;
		}
		#head {
			background: <?php echo $device_rgba; ?> url('<?php echo $shiword_colors['device_image']; ?>') right -0.7em repeat;
		}
		input[type="button"]:hover,
		input[type="submit"]:hover,
		input[type="reset"]:hover {
			border-color: <?php echo $shiword_colors['main4']; ?>;
		}
		#head a,
		#head .description,
		#statusbar {
			color: <?php echo $shiword_colors['device_textcolor']; ?>;
		}
		.menuitem:hover .menuitem_img,
		a {
			color : <?php echo $shiword_colors['main3']; ?>;
		}
		a:hover {
			color : <?php echo $shiword_colors['main4']; ?>;
		}
		.sw-menu,
		#mainmenu > li:hover,
		#mainmenu > li.page_item > ul.children,
		#mainmenu > li.menu-item > ul.sub-menu,
		.minibutton .nb_tooltip,
		.menuback {
			background-color: <?php echo $shiword_colors['menu1']; ?>;
			border-color: <?php echo $shiword_colors['menu2']; ?>;
			color: <?php echo $shiword_colors['menu3']; ?>;
		}
		.sw-menu a,
		.sw-menu > li.page_item > ul.children a,
		.sw-menu > li.menu-item > ul.sub-menu a,
		.menuback a {
			color: <?php echo $shiword_colors['menu4']; ?>;
		}
		.sw-menu a:hover,
		.menu-item-parent:hover > a:after,
		.current-menu-ancestor > a:after,
		.current-menu-parent > a:after,
		.current_page_parent > a:after,
		.current_page_ancestor > a:after,
		.sw-menu > li.page_item > ul.children a:hover,
		.sw-menu > li.menu-item > ul.sub-menu a:hover,
		.minibutton .nb_tooltip a:hover,
		.menuback a:hover,
		.sw-menu .current-menu-item > a,
		.sw-menu .current_page_item > a,
		.sw-menu .current-cat > a,
		.sw-menu a:hover,
		.sw-menu .current-menu-item a:hover,
		.sw-menu .current_page_item a:hover,
		.sw-menu .current-cat a:hover {
			color: <?php echo $shiword_colors['menu5']; ?>;
		}
		.menu_sx > ul {
			border-color: <?php echo $shiword_colors['menu6']; ?>;
		}
		.menuback .mentit {
			color: <?php echo $shiword_colors['menu3']; ?>;
		}
		.sticky {
			-moz-box-shadow: 0 0 8px <?php echo $shiword_colors['main3']; ?>;
			box-shadow: 0 0 8px <?php echo $shiword_colors['main3']; ?>;
			-webkit-box-shadow: 0 0 8px <?php echo $shiword_colors['main3']; ?>;
		}
		textarea:hover,
		input[type="text"]:hover,
		input[type="password"]:hover,
		textarea:focus,
		input[type="text"]:focus,
		input[type="password"]:focus {
			border:1px solid <?php echo $shiword_colors['main4']; ?>;
		}
		.social-like li a img {
			border: 1px solid <?php echo $shiword_colors['main3']; ?>;

		}
		.social-like li a img:hover {
			border: 1px solid <?php echo $shiword_colors['main4']; ?>;
		}
		.h2-ext-link {
			background-color: <?php echo $shiword_colors['main3']; ?>;
		}
		.h2-ext-link:hover {
			background-color: <?php echo $shiword_colors['main4']; ?>;
		}
		#infinite-handle span {
			border: 1px solid <?php echo $shiword_colors['main3']; ?>;
		}
		#infinite-handle span:hover {
			border: 1px solid <?php echo $shiword_colors['main4']; ?>;
		}
		.minib_img,
		.menuitem_img,
		.search-form,
		.comment-reply-link,
		.mft_date,
		.mft_cat,
		.mft_tag,
		.mft_comm,
		.mft_hier {
			background-image: url('<?php echo get_template_directory_uri(); ?>/images/minibuttons-<?php echo $shiword_colors['device_button_style']; ?>.png');
		}
		.TB_overlayBG,
		#TB_window,
		#TB_load {
			background-color: <?php echo $shiword_opt['shiword_thickbox_bg']; ?>;
		}
		#TB_window {
			color: <?php echo shiword_rgblight($shiword_opt['shiword_thickbox_bg']) < 125 ? '#fff' : '#000'; ?>;
		}
		#TB_secondLine {
			color: <?php echo shiword_rgblight($shiword_opt['shiword_thickbox_bg']) < 125 ? '#c0c0c0' : '#404040'; ?>;
		}
		.sw-has-thumb .post-body {
			margin-left: <?php echo $shiword_opt['shiword_pthumb_size'] + 10; ?>px;
		}
		body.rtl .sw-has-thumb .post-body {
			margin-right: <?php echo $shiword_opt['shiword_pthumb_size'] + 10; ?>px;
			margin-left: 0;
		}
		#sw_slider-wrap {
			height: <?php echo $shiword_opt['shiword_sticky_height']; ?>;
		}
		<?php 
			if ( $shiword_opt['shiword_custom_css'] )
				echo $shiword_opt['shiword_custom_css']; 
		?>
	</style>
	<!-- InternetExplorer really sucks! -->
	<!--[if lte IE 8]>
	<style type="text/css">
		#sw_body,
		#fixedfoot {
			background: <?php echo $shiword_colors['device_color']; ?> url('<?php echo $shiword_colors['device_image']; ?>') right top repeat;
		}
		#head {
			background: <?php echo $shiword_colors['device_color']; ?> url('<?php echo $shiword_colors['device_image']; ?>') right -0.7em repeat;
		}
		.storycontent img.size-full,
		.sd-post-title img,
		.gallery-item img {
			width:auto;
		}
	</style>
	<![endif]-->
<?php

	}
}


//set the excerpt lenght
function shiword_excerpt_length( $length ) {

	return (int) shiword_get_opt( 'shiword_xcont_lenght' );

}


// add links to admin bar
function shiword_admin_bar_plus() {
	global $wp_admin_bar;

	if ( !current_user_can( 'edit_theme_options' ) || !is_admin_bar_showing() )
		return;

	$add_menu_meta = array(
		'target'	=> '_blank'
	);
	$wp_admin_bar->add_menu( array(
		'id'		=> 'sw_theme_options',
		'parent'	=> 'appearance',
		'title'		=> __( 'Theme Options', 'shiword' ),
		'href'		=> get_admin_url() . 'themes.php?page=tb_shiword_functions',
		'meta'		=> $add_menu_meta,
	) );

}


// add 'quoted on' before trackback/pingback comments link
function shiword_add_quoted_on( $return ) {
	global $comment;

	$text = '';
	if ( get_comment_type() != 'comment' ) {
		$text = '<span class="sw-quotedon">' . __( 'quoted on', 'shiword' ) . ' </span>';
	}

	return $text . $return;

}


// the real comment count
function shiword_comment_count( $count ) {

	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments(get_comments( 'status=approve&post_id=' . $id));
		$count = count($comments_by_type['comment']);
	}

	return $count;

} 


// check if the current post has a format and if it's available
if ( !function_exists( 'shiword_is_post_format_available' ) ) {
	function shiword_is_post_format_available( $id ) {

		$is_available = get_post_format( $id ) && shiword_get_opt( 'shiword_postformat_' . get_post_format( $id ) );

		return $is_available;

	}
}


//Displays the amount of time since a post or page was written in a nice friendly manner.
//Based on Plugin: Date in a nice tone (http://wordpress.org/extend/plugins/date-in-a-nice-tone/)
if ( !function_exists( 'shiword_get_friendly_date' ) ) {
	function shiword_get_friendly_date() {

		$posttime = get_the_time( 'U' );
		$currenttime = time();
		$timedifference = $currenttime - $posttime;

		$mininsecs = 60;
		$hourinsecs = 3600;
		$dayinsecs = 86400;
		$monthinsecs = $dayinsecs * 31;
		$yearinsecs = $dayinsecs * 366;

		//if over 2 years
		if ( $timedifference > ( $yearinsecs * 2 ) ) {
			$datewithnicetone = __( 'quite a long while ago...', 'shiword' );

		//if over a year 
		} else if ( $timedifference > $yearinsecs ) {
			$datewithnicetone = __( 'over a year ago', 'shiword' );

		//if over 2 months
		} else if ( $timedifference > ( $monthinsecs * 2 ) ) {
			$num = round( $timedifference / $monthinsecs );
			$datewithnicetone = sprintf( __( '%s months ago', 'shiword' ), $num );

		//if over a month
		} else if ( $timedifference > $monthinsecs ) {
			$datewithnicetone = __( 'a month ago', 'shiword' );

		//if more than 2 days ago
		} else {
			$htd = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
			$datewithnicetone = sprintf( __( '%s ago', 'shiword' ), $htd );
		}

		return $datewithnicetone;

	}
}


// convert post content in blockquote for quote format posts)
function shiword_quote_content( $content ) {

	/* Check if we're displaying a 'quote' post. */
	if ( has_post_format( 'quote' ) && shiword_get_opt( 'shiword_postformat_quote' ) ) {

		/* Match any <blockquote> elements. */
		preg_match( '/<blockquote.*?>/', $content, $matches );

		/* If no <blockquote> elements were found, wrap the entire content in one. */
		if ( empty( $matches ) )
			$content = "<blockquote>{$content}</blockquote>";
	}

	return $content;

}


// custom image caption
function shiword_img_caption_shortcode( $deprecated, $attr, $content = null) {

	extract( shortcode_atts( array(
		'id'		=> '',
		'align'		=> 'alignnone',
		'width'		=> '',
		'caption'	=> '',
	), $attr) );

	if ( 1 > (int) $width || empty($caption) )
		return $content;

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . $width . 'px"><div class="wp-caption-inside">'
	. do_shortcode( $content ) . '<div class="wp-caption-text">' . $caption . '</div></div></div>';

}


// custom gallery shortcode function. supports 'ids' attribute (WP3.5)
function shiword_gallery_shortcode( $output, $attr ) {

	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract( shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr) );

	$id = intval( $id );
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( ! empty( $include ) ) {
		$_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $exclude ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
	}

	if ( isset( $attr['preview'] ) && $attr['preview'] )
		return shiword_gallery_preview_walker( $attachments, $id );

	if ( empty( $attachments ) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
		return $output;
	}

	$attr['link'] = shiword_get_opt( 'shiword_thickbox_link_to_image' ) ? 'file' : $attr['link'];

	$columns = intval( $columns );
	$itemwidth = $columns > 0 ? floor( 100/$columns ) : 100;

	$selector = "gallery-{$instance}";

	$size_class = sanitize_html_class( $size );
	$output = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link( $id, $size, false, false ) : wp_get_attachment_link( $id, $size, true, false );

		$output .= '
			<div class="gallery-item">';
		$output .= "
				<div class='gallery-item-inside'>
					$link";
		if ( trim( $attachment->post_excerpt ) ) {
			$output .= "
				<div class='gallery-caption'>
				" . wptexturize( $attachment->post_excerpt ) . "
				</div>";
		}
		$output .= "
				</div>
			</div>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n";

	return $output;

}


// strip tags from titles and apply title format for blank ones
function shiword_title_tags_filter( $title = '', $id = 0 ) {

	if ( is_admin() ) return $title;

	$title = strip_tags( $title, '<abbr><acronym><b><em><i><del><ins><bdo><strong><img><sub><sup><a>' );

	if ( ! shiword_get_opt( 'shiword_manage_blank_titles' ) ) return $title;

	if ( $id == 0 ) return $title;

	if ( empty( $title ) ) {
		if ( ! shiword_get_opt( 'shiword_blank_title' ) ) return '';
		$postdata = array( get_post_format( $id )? get_post_format_string( get_post_format( $id ) ): __( 'post', 'shiword' ), get_the_time( get_option( 'date_format' ), $id ), $id );
		$codes = array( '%f', '%d', '%n' );
		$title = str_replace( $codes, $postdata, shiword_get_opt( 'shiword_blank_title' ) );
	}

	return $title;

}


// use the "excerpt more" string as a link to the post
function shiword_new_excerpt_more( $more ) {
	global $post;

	if ( is_admin() ) return $more;
	if ( $text = shiword_get_opt( 'shiword_xcont_more_txt' ) ) {
		$more = shiword_get_opt( 'shiword_xcont_more_link' ) ? '<a href="' . esc_url( get_permalink() ) . '">' . $text . '</a>' : $text;
	}

	return $more;

}


// custom text for the "more" tag
function shiword_more_link( $more_link, $more_link_text = '' ) {

	if ( shiword_get_opt( 'shiword_more_tag' ) && !is_admin() ) {
		$more_text = str_replace ( '%t', get_the_title(), shiword_get_opt( 'shiword_more_tag' ) );
		$more_link = str_replace( $more_link_text, $more_text, $more_link );
	}

	return $more_link;

}


/**
 * Filter 'wp_title'
 */
function shiword_filter_wp_title( $title, $sep = '&laquo' ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'shiword' ), max( $paged, $page ) );

	return $title;

}


/**
 * Filter 'body_class'
 */
function shiword_filter_body_class( $classes ) {

	$classes[] = 'sw-no-js';
	$classes[] = 'body-' . shiword_get_opt( 'shiword_frame_width' );

	return $classes;

}


// Custom form fields for the comment form
function shiword_comments_form_fields( $fields ) {

	$commenter	= wp_get_current_commenter();
	$req		= get_option( 'require_name_email' );
	$aria_req	= ( $req ? " aria-required='true'" : '' );

	$custom_fields =  array(
		'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" />' .
					'<label for="author">' . __( 'Name', 'shiword' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
		'email'  => '<p class="comment-form-email">' . '<input id="email" name="email" type="text" value="' . sanitize_email(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" />' .
					'<label for="email">' . __( 'Email', 'shiword' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
		'url'    => '<p class="comment-form-url">' . '<input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '" size="30" />' .
					'<label for="url">' . __( 'Website', 'shiword' ) . '</label>' .'</p>',
	);

	return $custom_fields;

}


// filters comments_form() default arguments
function shiword_comment_form_defaults( $defaults ) {
	global $user_identity;

	$defaults['label_submit']	= __( 'Say It!', 'shiword' );
	$defaults['comment_field']	= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="7" aria-required="true"></textarea></p>';
	$defaults['logged_in_as']	= '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>.', 'shiword' ), esc_url( admin_url( 'profile.php' ) ), $user_identity ) . '</p>';

	return $defaults;

}


// the search form
function shiword_search_form( $form ) {

	if ( ! shiword_is_mobile() ) $form = '
					<div class="search-form">
						<form onsubmit="this.parentNode.className=\'search-form searching\';" action="' . esc_url( home_url( '/' ) ) . '" class="sw-searchform" method="get">
							<input type="text" onfocus="if (this.value == \'' . esc_attr__( 'Search', 'shiword' ) . '...\')
							{this.value = \'\';}" onblur="if (this.value == \'\')
							{this.value = \'' . esc_attr__( 'Search', 'shiword' ) . '...\';}" class="sw-searchinput" name="s" value="' . esc_attr__( 'Search', 'shiword' ) . '..." />
							<input type="hidden" class="sw-searchsubmit" />
						</form>
					</div>';
	return $form;

}


/**
 * Add parent class to wp_page_menu top parent list items
 */
function shiword_add_parent_class( $css_class, $page, $depth, $args ) {

	if ( ! empty( $args['has_children'] ) && $depth == 0 )
		$css_class[] = 'menu-item-parent';

	return $css_class;

}


/**
 * Add parent class to wp_nav_menu top parent list items
 */
function shiword_add_menu_parent_class( $items ) {

	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = $item->menu_item_parent;
		}
	}

	foreach ( $items as $item ) {
		if ( is_array( $parents ) && in_array( $item->ID, $parents ) ) {
			if ( ! $item->menu_item_parent )
				$item->classes[] = 'menu-item-parent'; 
		}
	}

	return $items;

}


// wrap the categories count with a span
function shiword_wrap_categories_count( $output ) {

	$pattern = '/<\/a>\s(\(\d+\))/i';
	$replacement = ' <span class="details">$1</span></a>';
	return preg_replace( $pattern, $replacement, $output );

}


// display a simple login form in quickbar
if ( !function_exists( 'shiword_mini_login' ) ) {
	function shiword_mini_login() {

		$args = array(
			'redirect'		=> home_url(),
			'form_id'		=> 'sw-loginform',
			'id_username'	=> 'sw-user_login',
			'id_password'	=> 'sw-user_pass',
			'id_remember'	=> 'sw-rememberme',
			'id_submit'		=> 'sw-submit',
		);

?>
	<li class="ql_cat_li">
		<a title="<?php esc_attr_e( 'Log in', 'shiword' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in', 'shiword' ); ?></a>
		<?php if ( shiword_get_opt( 'shiword_qbar_minilogin' ) && ( !class_exists("siCaptcha") ) ) { ?>
			<div id="sw_minilogin_wrap" class="cat_preview">
				<div class="mentit"><?php _e( 'Log in', 'shiword' ); ?></div>
				<div id="sw_minilogin" class="solid_ul">
					<?php wp_login_form($args); ?>
					<a id="closeminilogin" href="#"><?php _e( 'Close', 'shiword' ); ?></a>
				</div>
			</div>
		<?php } ?>
	</li>
<?php

	}
}


//non multibyte fix
if ( !function_exists( 'mb_strimwidth' ) ) {
	function mb_strimwidth( $string, $start, $length, $wrap = '&hellip;' ) {

		if ( strlen( $string ) > $length ) {
			$ret_string = substr( $string, $start, $length ) . $wrap;
		} else {
			$ret_string = $string;
		}

		return $ret_string;

	}
}


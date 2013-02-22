<?php
/**
 * The posts slider
 *
 * @package shiword
 * @since shiword 3.01
 */


add_action( 'admin_init', 'shiword_slider_update' );
add_action( 'admin_print_styles-edit.php', 'shiword_posts_table_style' );
add_action( 'shiword_hook_header_after', 'shiword_slider_init', 11 );


add_filter( 'post_row_actions', 'shiword_slider_add_posts_link', 10, 2 );
add_filter( 'page_row_actions', 'shiword_slider_add_posts_link', 10, 2 );
add_filter( 'display_post_states', 'shiword_slider_add_post_state' );


// add the black icon in posts/pages lists
function shiword_slider_add_post_state( $post_states ) {
	global $post;

	$posts_list = get_option( 'shiword_slideshow' ); //get the selected posts list

	if ( in_array( $post->ID, $posts_list ) )
		$post_states['slideshow'] = '<img class="in-slider-icon" src="' . get_template_directory_uri().'/images/inslider.png" alt="in slider" title="' . __( 'this post is added to the slideshow', 'shiword' ) . '" />';

	return $post_states;

}

// update the "shiword_slideshow" option 
function shiword_slider_update() {

	if ( isset( $_GET['slider_action'] ) && isset( $_GET['post'] ) ) {

		$post_id = (int) $_GET['post'];

		if ( $post_id == 0 ) return;


		$posts_list = get_option( 'shiword_slideshow' ); //get the selected posts list

		switch ( $_GET['slider_action'] ) {

			case 'add':
				check_admin_referer( 'add-to-slider_' . $post_id );
				$key = array_search( $post_id, $posts_list );
				if ( !$key ) {
					$posts_list[] = $post_id;
					update_option( 'shiword_slideshow' , $posts_list );
				}
				break;

			case 'remove':
				check_admin_referer( 'remove-from-slider_' . $post_id );
				$key = array_search( $post_id, $posts_list );
				if ( $key ) {
					unset( $posts_list[$key] );
					update_option( 'shiword_slideshow' , $posts_list );
				}
				break;

			default:
				// nop

		}

	}

}

// add the "add/remove" link in posts/pages lists
function shiword_slider_add_posts_link( $actions, $post ) {

	$posts_list = get_option( 'shiword_slideshow' ); //get the selected posts list

	if ( $post->post_status == 'publish' ) {
		if ( in_array( $post->ID, $posts_list ) )
			$actions['slideshow'] = "<a class='remove' href='" . wp_nonce_url( "edit.php?post_type=$post->post_type&amp;slider_action=remove&amp;post=$post->ID", 'remove-from-slider_' . $post->ID ) . "'>" . __( 'Remove from Slider', 'shiword' ) . "</a>";
		else
			$actions['slideshow'] = "<a class='add' href='" . wp_nonce_url( "edit.php?post_type=$post->post_type&amp;slider_action=add&amp;post=$post->ID", 'add-to-slider_' . $post->ID ) . "'>" . __( 'Add to Slider', 'shiword' ) . "</a>";
	}
	return $actions;

}

//add custom stylesheet
function shiword_posts_table_style() {
	wp_enqueue_style( 'shiword-posts-table-style', get_template_directory_uri() . '/css/admin-posts-table.css', false, '', 'screen' );
}


if ( !function_exists( 'shiword_slider_register_settings' ) ) {
	function shiword_slider_register_settings() {

		register_setting( 'shiw_slideshow_group', 'shiword_slideshow', 'shiword_slider_sanitize'  ); //register slideshow settings

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

// the slider init
function shiword_slider_init(){
	global $shiword_opt;

	if ( $shiword_opt['shiword_sticky'] == 1 && !is_404() ) {
		if (
			( is_page() && ( $shiword_opt['shiword_sticky_pages'] == 1 ) ) ||
			( is_single() && ( $shiword_opt['shiword_sticky_posts'] == 1 ) ) ||
			( is_front_page() && ( $shiword_opt['shiword_sticky_front'] == 1 ) ) ||
			( ( is_archive() || is_search() ) && ( $shiword_opt['shiword_sticky_over'] == 1 ) )
		) shiword_slider(); 
	}

}

// display a slideshow for the selected posts
if ( !function_exists( 'shiword_slider' ) ) {
	function shiword_slider() {
		global $post, $shiword_opt;

		if ( shiword_is_printpreview() ) return; // no slider in print preview

		$posts_list = get_option( 'shiword_slideshow' ); //get the selected posts list
		if ( !isset( $posts_list ) || empty( $posts_list ) ) return; // if no post is selected, exit
		$args = array(
			'post__in' => $posts_list,
			'post_type'=> 'any',
			'orderby' => 'post__in',
			'post_status' => 'publish',
			'no_found_rows' => true,
			'posts_per_page' => -1,
			'ignore_sticky_posts' => true
		);

		$r = new WP_Query( $args );
		if ($r->have_posts()) {
?>
			<div id="sw_slider-wrap">
				<div id="sw_sticky_slider">
<?php
				while ($r->have_posts()) {
					$r->the_post();
					$post_title = get_the_title();
					$post_author = isset( $shiword_opt['shiword_sticky_author'] ) && $shiword_opt['shiword_sticky_author'] == 0? '' : '<span class="sw-slider-auth">' . sprintf( __( 'by %s', 'shiword' ), get_the_author() ) . '</span>';
?>
						<div class="sss_item">
							<div class="sss_inner_item">
								<a href="<?php echo get_permalink(); ?>" title="<?php echo $post_title; ?>">
									<?php echo shiword_get_the_thumb( get_the_ID(), 120, 120, 'alignleft' ); ?>
								</a>
								<div style="padding-left: 130px;">
									<h2 class="storytitle"><a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a></h2> <?php echo $post_author; ?>
									<div class="storycontent">
										<?php the_excerpt(); ?>
									</div>
								</div>
							</div>
						</div>
<?php
				}
?>
				</div>
				<?php if ( $r->post_count > 1 ) { ?>
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
		wp_reset_postdata();
	}
}


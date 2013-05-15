<?php
/**
 * slider.php
 *
 * The posts slider stuff
 *
 * @package Shiword
 * @since 3.01
 */


class Shiword_Slider {

	function __construct() {

		add_action( 'admin_init'					, array( $this, 'update' ) );
		add_action( 'admin_print_styles-edit.php'	, array( $this, 'posts_table_style' ) );
		add_action( 'shiword_hook_header_after'		, array( $this, 'init' ), 11 );

		add_filter( 'post_row_actions'				, array( $this, 'add_posts_link' ), 10, 2 );
		add_filter( 'page_row_actions'				, array( $this, 'add_posts_link' ), 10, 2 );
		add_filter( 'display_post_states'			, array( $this, 'add_post_state' ) );

	}


	// add the icon in posts/pages lists
	function add_post_state( $post_states ) {
		global $post;

		$posts_list = get_option( 'shiword_slideshow' ); //get the selected posts list

		if ( is_array( $posts_list ) && in_array( $post->ID, $posts_list ) )
			$post_states['slideshow'] = '<img class="in-slider-icon" src="' . esc_url( get_template_directory_uri() . '/images/inslider.png' ) . '" alt="in slider" title="' . esc_attr__( 'this post is added to the slideshow', 'shiword' ) . '" />';

		return $post_states;

	}


	// add the "add/remove" link in posts/pages lists
	function add_posts_link( $actions, $post ) {

		$posts_list = get_option( 'shiword_slideshow' ); //get the selected posts list

		if ( $post->post_status == 'publish' ) {
			if ( is_array( $posts_list ) && in_array( $post->ID, $posts_list ) )
				$actions['slideshow'] = "<a class='remove' href='" . wp_nonce_url( "edit.php?post_type=$post->post_type&amp;slider_action=remove&amp;post=$post->ID", 'remove-from-slider_' . $post->ID ) . "'>" . __( 'Remove from Slider', 'shiword' ) . "</a>";
			else
				$actions['slideshow'] = "<a class='add' href='" . wp_nonce_url( "edit.php?post_type=$post->post_type&amp;slider_action=add&amp;post=$post->ID", 'add-to-slider_' . $post->ID ) . "'>" . __( 'Add to Slider', 'shiword' ) . "</a>";
		}
		return $actions;

	}


	// update the "shiword_slideshow" option 
	function update() {

		if ( isset( $_GET['slider_action'] ) && isset( $_GET['post'] ) ) {

			$post_id = (int) $_GET['post'];

			if ( $post_id == 0 ) return;


			$posts_list = get_option( 'shiword_slideshow', array() ); //get the selected posts list

			switch ( $_GET['slider_action'] ) {

				case 'add':
					check_admin_referer( 'add-to-slider_' . $post_id );
					$key = array_search( $post_id, $posts_list );
					if ( $key === false ) {
						$posts_list[] = $post_id;
						update_option( 'shiword_slideshow' , $posts_list );
					}
					break;

				case 'remove':
					check_admin_referer( 'remove-from-slider_' . $post_id );
					$key = array_search( $post_id, $posts_list );
					if ( $key !== false ) {
						unset( $posts_list[$key] );
						update_option( 'shiword_slideshow' , $posts_list );
					}
					break;

				default:
					// nop

			}

		}

	}


	//add custom stylesheet
	function posts_table_style() {

		wp_enqueue_style( 'shiword-posts-table-style', get_template_directory_uri() . '/css/admin-posts-table.css', false, '', 'screen' );

	}



	function register_settings() {

		register_setting( 'shiw_slideshow_group', 'shiword_slideshow', 'shiword_slider_sanitize'  ); //register slideshow settings

	}


	function sanitize( $input ){

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


	// the slider init
	function init(){

		if ( shiword_get_opt( 'shiword_sticky' ) && !is_404() ) {
			if (
				( is_page() && shiword_get_opt( 'shiword_sticky_pages' ) ) ||
				( is_single() && shiword_get_opt( 'shiword_sticky_posts' ) ) ||
				( is_front_page() && shiword_get_opt( 'shiword_sticky_front' ) ) ||
				( ( is_archive() || is_search() ) && shiword_get_opt( 'shiword_sticky_over' ) )
			) $this->the_slider(); 
		}

	}


	// display a slideshow for the selected posts
	function the_slider() {
		global $post;

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
					$post_author = ! shiword_get_opt( 'shiword_sticky_author' ) ? '' : '<span class="sw-slider-auth">' . sprintf( __( 'by %s', 'shiword' ), get_the_author() ) . '</span>';
?>
						<div class="sss_item">
							<div class="sss_inner_item">
								<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>">
									<?php echo shiword_get_the_thumb( array( 'width' => 120, 'height' => 120, 'class' => 'alignleft' ) ); ?>
								</a>
								<div style="padding-left: 130px;">
									<h2 class="storytitle"><a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2> <?php echo $post_author; ?>
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
				<div class="sw_slider-fade"> </div>
				<?php if ( $r->post_count > 1 ) { ?>
					<div class="sw_slider-skip toright"> </div>
					<div class="sw_slider-skip toleft"> </div>
				<?php } ?>
			</div>
<?php

		}

		wp_reset_postdata();

	}

}

new Shiword_Slider;

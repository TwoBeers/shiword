<?php
/**
 * plugins.php
 *
 * plugins support
 *
 * @package Shiword
 * @since 4.04
 */


// Jetpack support
class Shiword_For_Jetpack {

	function __construct () {

		add_action( 'init', array( $this, 'init' ) ); //Jetpack support

	}


	/* initialize Jetpack support */
	function init() {

		if ( shiword_is_mobile() ) return;

		//Infinite Scroll
		add_theme_support( 'infinite-scroll', array(
			'type'		=> 'click',
			'container'	=> 'posts-container',
			'render'	=> array( $this, 'infinite_scroll_render' ),
		) );
		if ( class_exists( 'The_Neverending_Home_Page' ) ) {
			add_filter(		'infinite_scroll_results',		array( $this, 'infinite_scroll_encode' ), 11, 1 );
		}

		//Sharedaddy
		if ( function_exists( 'sharing_display' ) ) {
			remove_filter(	'the_content',					'sharing_display', 19 );
			remove_filter(	'the_excerpt',					'sharing_display', 19 );
			add_filter(		'shiword_like_it',				'sharing_display', 19 );
		}

		//Carousel
		if ( class_exists( 'Jetpack_Carousel' ) ) {
			remove_filter(	'post_gallery',					'shiword_gallery_shortcode', 10, 2 );
			add_filter(		'shiword_filter_js_modules',	array( $this, 'carousel' ) );
		}

		//Likes
		if ( class_exists( 'Jetpack_Likes' ) ) {
			add_filter(		'wpl_is_index_disabled',		'__return_false' );
		}

	}


	//Set the code to be rendered on for calling posts,
	function infinite_scroll_render() {

		get_template_part( 'loop' );

	}


	//encodes html result to UTF8 (jetpack bug?)
	//http://localhost/wordpress/?infinity=scrolling&action=infinite_scroll&page=5&order=DESC
	function infinite_scroll_encode( $results ) {

		$results['html'] = utf8_encode( utf8_decode( $results['html'] ) );
		return $results;

	}


	//skip the thickbox js module
	function carousel( $modules ) {

		$modules = str_replace( 'thickbox', 'carousel', $modules );
		return $modules;

	}

}

new Shiword_For_Jetpack;



// Addthis support
class Shiword_For_AddThis {

	function __construct () {

		add_filter( 'init', array( $this, 'init' ) ); //Addthis support

	}


	/* initialize Addthis support */
	function init() {

		add_filter(		'shiword_like_it'			, array( $this, 'social_widget' ) );

	}


	// get the output text
	function social_widget() {

		$output = '';

		if ( function_exists( 'addthis_display_social_widget' ) ) {

			remove_filter(	'addthis_above_content'		, '__return_false', 99 );
			add_filter(		'addthis_below_content'		, '__return_false', 99 );

			$output = addthis_display_social_widget( '' );

			add_filter(		'addthis_above_content'		, '__return_false', 99 );
			remove_filter(	'addthis_below_content'		, '__return_false', 99 );

		}

		return $output;

	}

}

new Shiword_For_AddThis;



/**
 * Functions and hooks for bbPress integration
 */
class Shiword_bbPress {

	function __construct() {

		if ( ! function_exists( 'is_bbpress' ) ) return;

		add_action( 'wp_head'								, array( $this, 'init' ), 999 );
		add_filter( 'shiword_options_array'					, array( $this, 'extra_options' ), 10, 1 );

	}


	/**
	 * Filters and hooks initialization
	 */
	function init() {

		if ( ! is_bbpress() ) return;

		add_filter( 'shiword_option_shiword_xinfos_global'			, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_print'		, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_comment'		, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_feed'		, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_trackback'	, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_nextprev'	, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_newold'		, '__return_false' );
		add_filter( 'shiword_like_it'								, '__return_false', 999 );
		add_filter( 'shiword_skip_single_widgets_area'				, '__return_true' );
		add_filter( 'shiword_get_layout'							, array( $this, 'get_layout' ) );

	}


	function extra_options( $coa ) {

		$coa['shiword_rsideb_bbpress'] = array(
			'group'				=> 'sidebar',
			'type'				=> 'chk',
			'default'			=> 0,
			'description'		=> __( 'on bbPress forums', 'shiword' ),
			'info'				=> '',
			'req'				=> '',
			'sub'				=> false
		);

		$coa['shiword_rsideb_group']['sub'][] = 'shiword_rsideb_bbpress';

		return $coa;

	}


	function get_layout( $layout ) {

		$layout = shiword_get_opt( 'shiword_rsideb_bbpress' ) ? 'narrow' : 'wide';

		return $layout;

	}

}

new Shiword_bbPress;



/**
 * Functions and hooks for BuddyPress integration
 */
class Shiword_BuddyPress {

	function __construct() {

		if ( ! function_exists( 'is_buddypress' ) ) return;

		add_action( 'wp_head'										, array( $this, 'init' ), 999 );
		add_filter( 'shiword_options_array'							, array( $this, 'extra_options' ), 10, 1 );

	}


	/**
	 * Filters and hooks initialization
	 */
	function init() {

		if ( ! is_buddypress() ) return;

		add_filter( 'shiword_option_shiword_xinfos_global'			, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_print'		, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_comment'		, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_feed'		, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_trackback'	, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_nextprev'	, '__return_false' );
		add_filter( 'shiword_option_shiword_navbuttons_newold'		, '__return_false' );
		add_filter( 'shiword_like_it'								, '__return_false', 999 );
		add_filter( 'shiword_skip_single_widgets_area'				, '__return_true' );
		add_filter( 'shiword_get_layout'							, array( $this, 'get_layout' ) );

	}


	function extra_options( $coa ) {

		$coa['shiword_rsideb_buddypress'] = array(
			'group'				=> 'sidebar',
			'type'				=> 'chk',
			'default'			=> 0,
			'description'		=> __( 'on BuddyPress pages', 'shiword' ),
			'info'				=> '',
			'req'				=> '',
			'sub'				=> false
		);

		$coa['shiword_rsideb_group']['sub'][] = 'shiword_rsideb_buddypress';

		return $coa;

	}


	function get_layout( $layout ) {

		$layout = shiword_get_opt( 'shiword_rsideb_buddypress' ) ? 'narrow' : 'wide';

		return $layout;

	}

}

new Shiword_BuddyPress;

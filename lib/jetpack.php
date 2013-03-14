<?php
/**
 * jetpack.php
 *
 * Jetpack support
 *
 * @package Shiword
 * @since 3.04
 */


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
			remove_action(	'shiword_hook_like_it',			'shiword_like_it' );
			add_action(		'shiword_hook_like_it',			array( $this, 'sharedaddy' ) );
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

		$page = (int) $_GET['page'] + 1;
		echo '<div class="page-reminder"><span>' . $page . '</span></div>';

		get_template_part( 'loop' );

	}


	//print page number reminder
	function infinite_scroll_navigation( $classes ) {

		$classes[] = 'hide-if-js';
		return $classes;

	}


	//encodes html result to UTF8 (jetpack bug?)
	//http://localhost/wordpress/?infinity=scrolling&action=infinite_scroll&page=5&order=DESC
	function infinite_scroll_encode( $results ) {

		$results['html'] = utf8_encode( utf8_decode( $results['html'] ) );
		return $results;

	}


	//print the sharedaddy buttons inside the "I-like-it" container instead of after post content
	function sharedaddy() {

		$text = sharing_display();
		if ( $text )
			echo '<div class="sw-I-like-it">' . $text . '<div class="fixfloat"> </div></div>';

	}


	//skip the thickbox js module
	function carousel( $modules ) {

		$modules = str_replace( 'thickbox', 'carousel', $modules );
		return $modules;

	}

}

new Shiword_For_Jetpack;

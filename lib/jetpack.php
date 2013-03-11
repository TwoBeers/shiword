<?php
/**
 * jetpack.php
 *
 * Jetpack support
 *
 * @package Shiword
 * @since 3.04
 */


add_action( 'init', 'shiword_for_jetpack_init' ); //Jetpack support

add_action( 'infinite_scroll_render', 'shiword_infinite_scroll_pageinfo' ); // page reminder


/* initialize Jetpack support */
function shiword_for_jetpack_init() {

	//Infinite Scroll
	add_theme_support( 'infinite-scroll', array(
		'type'		=> 'click',
		'container'	=> 'posts-container',
		'render'	=> 'shiword_for_jetpack_infinite_scroll',
	) );
	if ( class_exists( 'The_Neverending_Home_Page' ) )
		add_filter( 'shiword_filter_navi_classes', 'shiword_for_jetpack_infinite_scroll_navigation' );

	//Sharedaddy
	if ( function_exists( 'sharing_display' ) ) {
		remove_filter( 'the_content', 'sharing_display', 19 );
		remove_filter( 'the_excerpt', 'sharing_display', 19 );
		remove_action( 'shiword_hook_like_it', 'shiword_like_it' );
		add_action( 'shiword_hook_like_it', 'shiword_for_jetpack_sharedaddy' );
	}

	//Carousel
	if ( class_exists( 'Jetpack_Carousel' ) ) {
		remove_filter( 'post_gallery', 'shiword_gallery_shortcode', 10, 2 );
		add_filter( 'shiword_filter_js_modules', 'shiword_for_jetpack_carousel' );
	}

	//Likes
	if ( class_exists( 'Jetpack_Likes' ) ) {
		add_filter( 'wpl_is_index_disabled', '__return_false' );
	}

}

//Set the code to be rendered on for calling posts,
function shiword_for_jetpack_infinite_scroll() {

	get_template_part( 'loop' );

}

//print page number reminder
function shiword_infinite_scroll_pageinfo() {

	$page = (int) $_GET['page'] + 1;
	echo '<div class="page-reminder"><span>' . $page . '</span></div>';

}

//print page number reminder
function shiword_for_jetpack_infinite_scroll_navigation( $classes ) {

	$classes[] = 'hide-if-js';
	return $classes;

}

//print the sharedaddy buttons inside the "I-like-it" container instead of after post content
function shiword_for_jetpack_sharedaddy() {

	$text = sharing_display();
	if ( $text )
		echo '<div class="sw-I-like-it">' . $text . '<div class="fixfloat"> </div></div>';

}

//skip the thickbox js module
function shiword_for_jetpack_carousel( $modules ) {

	$modules = str_replace( 'thickbox', 'carousel', $modules );
	return $modules;

}

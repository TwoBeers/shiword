<?php
/**
 * breadcrumb.php
 *
 * The breadcrumb class.
 * Supports Yoast Breadcrumbs plugins.
 *
 * @package Shiword
 * @since 4.04
 */


class Shiword_Breadcrumb {

	static $instance = false;

	public static function init() {
		if ( ! self::$instance ) {
			self::$instance = new Shiword_Breadcrumb;
		}

		return self::$instance;
	}


	function __construct() {

		add_action( 'shiword_hook_header_after', array( $this, 'display' ), 12 );

		add_filter( 'shiword_breadcrumb', array( $this, 'yoast_breadcrumb' ) );

	}


	function yoast_breadcrumb( $output ) {

		if ( function_exists( 'yoast_breadcrumb' ) ) { // Yoast Breadcrumbs

			$output = yoast_breadcrumb( '', '', false );

		}

		return $output;

	}


	function display() {

		$output = apply_filters( 'shiword_breadcrumb', '' );

		if ( $output ) {

			echo '<div id="main-breadcrumb-navigation" class="fixfloat">' . $output . '</div>';

		} else {

			shiword_hook_breadcrumb();

		}


	}

}

add_action( 'init', array( 'Shiword_Breadcrumb', 'init' ) );

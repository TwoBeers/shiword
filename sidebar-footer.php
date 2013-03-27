<?php
/**
 * sidebar-footer.php
 *
 * Template part file that contains the footer widget area
 *
 * @package Shiword
 * @since 1.00
 */
?>

<!-- here should be the Footer widget area -->
<?php
	/* The footer widget area is triggered if any of the areas have widgets. */
	if ( !is_active_sidebar( 'first-footer-widget-area'  ) && !is_active_sidebar( 'second-footer-widget-area' ) && !is_active_sidebar( 'third-footer-widget-area'  )	&& !is_active_sidebar( 'fourth-footer-widget-area' ) ) {
		return;
	}
?>

<div id="footer-widget-area">

	<div class="fixfloat">
		<?php shiword_hook_sidebar_top(); ?>
		<?php shiword_hook_this_sidebar_top( 'footer' ); ?>
	</div>

	<div id="first_fwa" class="widget-area">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) dynamic_sidebar( 'first-footer-widget-area' ); ?>
	</div><!-- #first .widget-area -->

	<div id="second_fwa" class="widget-area">
			<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) dynamic_sidebar( 'second-footer-widget-area' ); ?>
	</div><!-- #second .widget-area -->

	<div id="third_fwa" class="widget-area">
			<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) dynamic_sidebar( 'third-footer-widget-area' ); ?>
	</div><!-- #third .widget-area -->

	<div class="fixfloat">
		<?php shiword_hook_this_sidebar_bottom( 'footer' ); ?>
		<?php shiword_hook_sidebar_bottom(); ?>
	</div>

</div>

<!-- end Footer widget area -->

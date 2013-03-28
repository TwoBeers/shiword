<?php
/**
 * sidebar-single.php
 *
 * Template part file that contains the widget area for
 * single posts/pages
 *
 * @package Shiword
 * @since 3.00
 */
?>

<!-- here should be the Single widget area -->
<?php
	/* The Single widget area is triggered if any of the areas have widgets. */
	if ( !is_active_sidebar( 'single-widget-area'  ) ) {
		return;
	}
?>

<?php shiword_hook_sidebars_before( 'single' ); ?>

<div id="single-widget-area">

	<?php shiword_hook_sidebar_top( 'single' ); ?>

	<div><?php dynamic_sidebar( 'single-widget-area' ); ?></div>

	<div class="fixfloat"> </div> 

	<?php shiword_hook_sidebar_bottom( 'single' ); ?>

</div>

<!-- end Single widget area -->

<?php shiword_hook_sidebars_after( 'single' ); ?>

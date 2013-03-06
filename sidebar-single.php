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

<div id="single-widget-area">

	<div class="fixfloat"><?php shiword_hook_sidebar_top(); ?></div> 

	<div><?php dynamic_sidebar( 'single-widget-area' ); ?></div>

	<div class="fixfloat"><?php shiword_hook_sidebar_bottom(); ?></div> 

</div>

<!-- end Single widget area -->
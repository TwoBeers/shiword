<?php
/**
 * sidebar-header.php
 *
 * Template part file that contains the header widget area
 *
 * @package shiword
 * @since shiword 1.00
 */
?>

<!-- here should be the Header widget area -->
<?php
	/* The Header widget area is triggered if any of the areas have widgets. */
	if ( !is_active_sidebar( 'header-widget-area'  ) ) {
		return;
	}
?>

<div id="header-widget-area">

	<div class="fixfloat"><?php shiword_hook_sidebar_top(); ?></div> 

	<div><?php dynamic_sidebar( 'header-widget-area' ); ?></div>

	<div class="fixfloat"><?php shiword_hook_sidebar_bottom(); ?></div> 

</div>

<!-- end Header widget area -->
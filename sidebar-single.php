<!-- here should be the Single widget area -->
<?php
	/* The Single widget area is triggered if any of the areas have widgets. */
	if ( !is_active_sidebar( 'single-widget-area'  ) ) {
		return;
	}
?>

<div id="single-widget-area">
	<?php if ( is_active_sidebar( 'single-widget-area' ) ) { ?>
		<?php dynamic_sidebar( 'single-widget-area' ); ?>
	<?php } ?>
	<div class="fixfloat"></div> 
</div><!-- #single-widget-area -->

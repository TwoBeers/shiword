<!-- here should be the Header widget area -->
<?php
	/* The Header widget area is triggered if any of the areas have widgets. */
	if (   ! is_active_sidebar( 'header-widget-area'  )	) return;
?>

<div id="header-widget-area">
	<?php if ( is_active_sidebar( 'header-widget-area' ) ) { ?>
		<div>
			<?php dynamic_sidebar( 'header-widget-area' ); ?>
		</div>
	<?php } ?>
	<div class="fixfloat"></div> 
</div><!-- #header-widget-area -->

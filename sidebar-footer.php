<!-- here should be the footer widget area -->
<?php
	/* The footer widget area is triggered if any of the areas have widgets. */
	if (   ! is_active_sidebar( 'first-footer-widget-area'  )
		&& ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area'  )
		&& ! is_active_sidebar( 'fourth-footer-widget-area' )
	)
		return;
?>

<div id="footer-widget-area">
	<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) { ?>
		<div id="first_fwa" class="widget-area">
			<div class="ul_fwa">
				<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
			</div>
		</div><!-- #first .widget-area -->
	<?php } ?>

	<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) { ?>
		<div id="second_fwa" class="widget-area">
			<div class="ul_fwa">
				<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
			</div>
		</div><!-- #second .widget-area -->
	<?php } ?>

	<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) { ?>
		<div id="third_fwa" class="widget-area">
			<div class="ul_fwa">
				<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
			</div>
		</div><!-- #third .widget-area -->
	<?php } ?>
	<div class="fixfloat"></div> 
</div><!-- #footer-widget-area -->

<?php
/**
 * sidebar-header.php
 *
 * Template part file that contains the header widget area
 *
 * @package Shiword
 * @since 1.00
 */
?>

<!-- here should be the Header widget area -->
<?php
	/* The Header widget area is triggered if any of the areas have widgets. */
	if ( !is_active_sidebar( 'header-widget-area'  ) ) {
		return;
	}
?>

<?php shiword_hook_sidebars_before( 'header' ); ?>

<div id="header-widget-area">

	<?php shiword_hook_sidebar_top( 'header' ); ?>

	<div><?php dynamic_sidebar( 'header-widget-area' ); ?></div>

	<div class="fixfloat"> </div> 

	<?php shiword_hook_sidebar_bottom( 'header' ); ?>

</div>

<!-- end Header widget area -->

<?php shiword_hook_sidebars_after( 'header' ); ?>

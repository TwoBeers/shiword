<?php
/**
 * sidebar-error404.php
 *
 * Template part file that contains the error404 widget area
 *
 * @package Shiword
 * @since 4.00
 */
?>

<?php shiword_hook_sidebars_before( 'error404' ); ?>

<div id="error404-widget-area">

	<?php shiword_hook_sidebar_top( 'error404' ); ?>

	<?php dynamic_sidebar( 'error404-widgets-area' ); ?>

	<?php shiword_hook_sidebar_bottom( 'error404' ); ?>

</div>

<?php shiword_hook_sidebars_after( 'error404' ); ?>

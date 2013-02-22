<?php
/**
 * 404.php
 *
 * This file is the Error 404 Page template file, which is output whenever
 * the server encounters a "404 - file not found" error.
 *
 * @package shiword
 * @since shiword 1.00
 */
?>

<?php get_header(); ?>

<?php shiword_get_layout(); ?>

<div class="<?php shiword_content_class(); ?>">

	<div class="post error404 not-found" id="post-0">

		<h2 class="storytitle">Error 404 - <?php _e( 'Page not found','shiword' ); ?></h2>

		<p><?php _e( "Sorry, you're looking for something that isn't here" ,'shiword' ); ?>: <u><?php echo home_url() . esc_html( $_SERVER['REQUEST_URI'] ); ?></u></p>
		<br>

		<?php shiword_hook_sidebar_404_before(); ?>

		<?php if ( is_active_sidebar( '404-widgets-area' ) ) { ?>
			<p><?php _e( 'Here is something that might help:','shiword' ); ?></p>
			<div id="error404-widget-area">
				<?php dynamic_sidebar( '404-widgets-area' ); ?>
			</div>
		<?php } else { ?>
			<p><?php _e( "There are several links scattered around the page, maybe they can help you on finding what you're looking for.", 'shiword' ); ?></p>
			<p><?php _e( 'Perhaps using the search form will help too...', 'shiword' ); ?></p>
			<?php get_search_form(); ?>
		<?php } ?>

		<div class="fixfloat"> </div>

		<?php shiword_hook_sidebar_404_after(); ?>

	</div>

</div>

<?php shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
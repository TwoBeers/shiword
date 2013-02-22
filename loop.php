<?php
/**
 * The main loop that displays posts.
 *
 *
 * @package Shiword
 * @since Shiword 3.05
 */
?>

<?php if ( have_posts() ) {

	while ( have_posts() ) {

		the_post(); ?>

		<?php shiword_hook_entry_before(); ?>

		<?php locate_template( array( 'loop/post-' . shiword_get_format() . '.php', 'loop/post.php' ), true, false ); ?>

		<?php shiword_hook_entry_after(); ?>

	<?php } ?>

	<?php shiword_paged_navi(); ?>

<?php } else { ?>

	<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>

<?php } ?>

<?php
/**
 * single.php
 *
 * The single blog post template file, used to display single blog posts.
 *
 * @package Shiword
 * @since 1.00
 */


get_header(); ?>

<?php shiword_get_layout( 'post' ); ?>

<div id="posts-container" class="<?php shiword_content_class(); ?>">

	<?php if ( have_posts() ) {

		while ( have_posts() ) {

			the_post(); ?>

			<?php shiword_navlinks(); ?>

			<?php shiword_hook_entry_before(); ?>

			<?php locate_template( array( 'single/post-' . shiword_get_format() . '.php', 'single/post.php' ), true, false ); ?>

			<?php shiword_hook_entry_after(); ?>

			<?php shiword_get_sidebar( 'single' ); // show single widget area ?>

			<?php comments_template(); // Get wp-comments.php template ?>

			<?php shiword_navlinks( 'bottom' ); ?>

		<?php } ?>

	<?php } else { ?>

		<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>

	<?php } ?>

</div>

<?php shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

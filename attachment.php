<?php
/**
 * attachment.php
 *
 * Template for attachment pages
 *
 * @package Shiword
 * @since 1.00
 */
?>

<?php get_header(); ?>

<?php shiword_get_layout( 'attachment' ); ?>

<div class="<?php shiword_content_class(); ?>">

<?php if ( have_posts() ) {

	while ( have_posts() ) {

		the_post(); ?>

		<?php shiword_hook_entry_before(); ?>

		<div <?php post_class( 'sw-entry-standard' ) ?> id="post-<?php the_ID(); ?>">

			<?php shiword_hook_entry_top(); ?>

			<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<?php shiword_hook_like_it(); ?>

			<?php shiword_extrainfo( array( 'hiera' => 0, 'tags' => 0, 'cats' => 0 ) ); ?>

			<div class="storycontent">

				<div class="entry-attachment">

					<?php if ( wp_attachment_is_image() ) { // is an image ?>

						<?php shiword_hook_attachment_before(); ?>

						<p class="attachment">
							<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php _e( 'View full size','shiword' ); ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, 'full' ); ?></a>
						</p>

						<?php shiword_hook_attachment_after(); ?>

						<?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?>

						<?php if ( !empty( $post->post_content ) ) the_content(); ?>

					<?php } else { // is an generic attachment ?>

						<?php echo wp_get_attachment_link( $post->ID, 'thumbnail', 0, 1 ); ?>

						<div class="entry-caption">
							<?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?>
						</div>

					<?php } ?>

				</div><!-- .entry-attachment -->

			</div>

			<div class="fixfloat"> </div>

			<?php shiword_hook_entry_bottom(); ?>

		</div>

		<?php shiword_hook_entry_after(); ?>

		<?php shiword_get_sidebar( 'single' ); // show single widget area ?>

		<?php comments_template(); // Get wp-comments.php template ?>

		<?php	} //end while
	} else {?>

		<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>

	<?php } ?>
</div>

<?php shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

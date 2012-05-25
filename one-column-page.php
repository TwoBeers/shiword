<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 */
?>
<?php get_header(); ?>

<div class="posts_wide">

<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		<?php shiword_hook_before_post(); ?>
		<div <?php post_class( 'sw-entry-standard' ) ?> id="post-<?php the_ID(); ?>">
			<?php shiword_hook_before_post_title(); ?>
			<?php shiword_post_title( array( 'fallback' => get_the_time( get_option( 'date_format' ) ), 'featured' => 1 ) ); ?>
			<?php shiword_hook_after_post_title(); ?>
			<?php shiword_I_like_it(); ?>
			<?php shiword_extrainfo( array( 'auth' => 0, 'date' => 0, 'tags' => 0, 'cats' => 0 ) ); ?>
			<div class="storycontent">
				<?php the_content();	?>
			</div>
			<div class="fixfloat">
				<?php wp_link_pages( 'before=<div class="meta sw-paginated-entry">' . __( 'Pages', 'shiword' ) . ':&after=</div>' ); ?>
			</div>
			<div class="fixfloat"> </div>
		</div>	
		<?php shiword_hook_after_post(); ?>
		
		<?php shiword_get_sidebar( 'single' ); // show single widget area ?>

		<?php comments_template(); // Get wp-comments.php template ?>
		
	<?php } 
} else { ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>
<?php } ?>
</div>

<?php get_footer(); ?>

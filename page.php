<?php get_header(); ?>
<?php
	$sw_use_side = ( ( $shiword_opt['shiword_rsideb'] == 0 ) || ( ( $shiword_opt['shiword_rsideb'] == 1 ) && ( $shiword_opt['shiword_rsidebpages'] == 0 ) ) ) ? false : true; 
	$sw_postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $sw_postswidth; ?>">

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
				<?php wp_link_pages( 'before=<div class="meta comment_tools" style="text-align: right;">' . __( 'Pages:', 'shiword' ) . '&after=</div><div class="fixfloat"></div>' ); ?>
			</div>
			<div class="fixfloat"> </div>
		</div>	
		<?php shiword_hook_after_post(); ?>
		
		<?php get_sidebar( 'single' ); // show single widget area ?>
		
		<?php comments_template(); // Get wp-comments.php template ?>
		
	<?php } 
} else { ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>
<?php } ?>
</div>

<?php if ( $sw_use_side ) get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

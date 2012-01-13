<?php get_header(); ?>
<?php
	$sw_use_side = ( ( $shiword_opt['shiword_rsideb'] == 0 ) || ( ( $shiword_opt['shiword_rsideb'] == 1 ) && ( $shiword_opt['shiword_rsidebposts'] == 0 ) ) ) ? false : true; 
	$sw_postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $sw_postswidth; ?>">

<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		
		<?php if ( post_password_required() ) {
			$sw_use_format = 'protected';
		} else {
			$sw_use_format = ( function_exists( 'get_post_format' ) && isset( $shiword_opt['shiword_postformat_' . get_post_format( $post->ID ) ] ) && $shiword_opt['shiword_postformat_' . get_post_format( $post->ID ) ] == 1 ) ? get_post_format( $post->ID ) : 'standard' ;
		} ?>
		<?php shiword_navlinks(); ?>
		
		<?php shiword_hook_before_post(); ?>
		<?php get_template_part( 'single/post', $sw_use_format ); ?>
		<?php shiword_hook_after_post(); ?>
		
		<?php get_sidebar( 'single' ); // show single widget area ?>
				
		<?php comments_template(); // Get wp-comments.php template ?>
		
		<?php shiword_navlinks( 'bottom' ); ?>
		
	<?php }
} else { ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>
<?php } ?>
</div>

<?php if ( $sw_use_side ) get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

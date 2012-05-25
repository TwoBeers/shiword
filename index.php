<?php get_header(); ?>

<?php global $shiword_opt; ?>

<?php
	$sw_use_side = ( ( $shiword_opt['shiword_rsideb'] == 0 ) || ( is_single() && ( $shiword_opt['shiword_rsideb'] == 1 ) && ( $shiword_opt['shiword_rsidebposts'] == 0 ) ) ) ? false : true; 
	$sw_posts_width = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
	$sw_show_thumb = ( $shiword_opt['shiword_pthumb'] ) ? ' sw-has-thumb' : '';
?>

<?php shiword_hook_before_posts(); ?>
<div class="<?php echo $sw_posts_width . $sw_show_thumb; ?>">

<?php shiword_search_reminder(); ?>

<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		
		<?php if ( post_password_required() ) {
			$sw_use_format = 'protected';
		} else {
			$sw_use_format = shiword_is_post_format_available( $post->ID ) ? get_post_format( $post->ID ) : '' ;
		} ?>
		<?php shiword_hook_before_post(); ?>
		<?php locate_template( array( 'loop/post-' . $sw_use_format . '.php', 'loop/post.php' ), true, false ); ?>
		<?php shiword_hook_after_post(); ?>
		
	<?php } ?>
	<div class="navigate_comments">
		<?php if ( function_exists( 'wp_pagenavi' ) ) { ?>
			<?php wp_pagenavi(); ?>
		<?php } else { ?>
			<?php //num of pages
				global $paged;
				if ( !$paged ) { $paged = 1; }
				if ( $shiword_opt['shiword_navlinks'] == 1 ) { previous_posts_link( '&laquo;' ); }
				printf( '<span>' . __( 'page %1$s of %2$s', 'shiword' ) . '</span>', $paged, $wp_query->max_num_pages );
				if ( $shiword_opt['shiword_navlinks'] == 1 ) { next_posts_link( '&raquo;'); }
			?>
		<?php } ?>
	</div>
<?php } else { ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>
<?php } ?>
</div>
<?php shiword_hook_after_posts(); ?>

<?php if ( $sw_use_side ) shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

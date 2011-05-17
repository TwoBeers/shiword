<?php get_header(); ?>
<?php global $shiword_opt, $query_string; ?>
<?php $author = get_queried_object(); ?>
<?php
	$sw_use_side = ( $shiword_opt['shiword_rsideb'] == 0 ) ? false : true; 
	$postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $postswidth; ?> letsstick">

	<div class="meta">
		<p style="text-align: center;"><?php printf( __( 'Posts by %s', 'shiword'), '<strong style="font-size: 15px; color: #fff;">' . wp_title( '',false ) . '</strong>'); ?></p>
		<?php if ( $author->description ) { // If a user has filled out their description, show a bio on their entries ?>
			<div id="entry-author-info">
				<?php echo get_avatar( $author->user_email, 32, $default= get_template_directory_uri() . '/images/user.png','user-avatar' ); ?>
				<?php
					if ( $author->twitter ) echo '<a title="' . sprintf( __('follow %s on Twitter', 'shiword'), $author->display_name ) . '" href="'.$author->twitter.'"><img alt="twitter" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Twitter.png" /></a>';
					if ( $author->facebook ) echo '<a title="' . sprintf( __('follow %s on Facebook', 'shiword'), $author->display_name ) . '" href="'.$author->facebook.'"><img alt="facebook" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Facebook.png" /></a>';
				?>
				<?php echo $author->description; ?>
				<div class="fixfloat" ></div>
			</div><!-- #entry-author-info -->
		<?php } ?>
	</div>
<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		
		<?php if ( post_password_required() ) {
			$sw_use_format = 'protected';
		} else {
			$sw_use_format = ( function_exists( 'get_post_format' ) && isset( $shiword_opt['shiword_postformat_' . get_post_format( $post->ID ) ] ) && $shiword_opt['shiword_postformat_' . get_post_format( $post->ID ) ] == 1 ) ? get_post_format( $post->ID ) : 'standard' ;
		} ?>
		
		<?php get_template_part( 'loop/post', $sw_use_format ); ?>
		
	<?php } ?>
	<div class="w_title navigate_comments" style="border-bottom:none;">
		<?php //num of pages
			global $paged;
			if ( !$paged ) { $paged = 1; }
			if ( $shiword_opt['shiword_navlinks'] == 1 ) { previous_posts_link( '&laquo;' ); }
			printf( '<span>' . __( 'page %1$s of %2$s', 'shiword' ) . '</span>', $paged, $wp_query->max_num_pages );
			if ( $shiword_opt['shiword_navlinks'] == 1 ) { next_posts_link( '&raquo;'); }
		?>
	</div>
<?php } else { ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>
<?php } ?>
</div>

<?php if ( $sw_use_side ) get_sidebar(); // show sidebar ?>
<?php get_footer(); ?>

<?php get_header(); ?>
<?php global $shiword_opt; ?>

<?php
	$sw_use_side = ( ( $shiword_opt['shiword_rsideb'] == 0 ) || ( is_single() && ( $shiword_opt['shiword_rsideb'] == 1 ) && ( $shiword_opt['shiword_rsidebposts'] == 0 ) ) ) ? false : true; 
	$postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $postswidth; ?> letsstick">

<?php if ( is_archive() ) { // archive reminder ?>
	<div class="meta">
		<p style="text-align: center;">
		<?php 
			if ( is_category() )	{ $strtype = __( 'Category', 'shiword' ) . ' : %s'; }
			elseif ( is_tag() )		{ $strtype = __( 'Tag', 'shiword' ) . ' : %s'; }
			elseif ( is_date() )	{ $strtype = __( 'Archives', 'shiword' ) . ' : %s'; }
		?>
		<?php printf( $strtype, '<strong style="font-size: 15px; color: #fff;">' . wp_title( '',false ) . '</strong>'); ?>
		</p>
	</div>
<?php } elseif ( is_search() ) { // search reminder ?>
	<div class="meta">
		<p style="text-align: center;">
		<?php printf( __( 'Search results for &#8220;%s&#8221;', 'shiword' ), '<strong style="font-size: 15px; color: #fff;">' . esc_html( get_search_query() ) . '</strong>' ); ?>
		</p>
	</div>
<?php } ?>

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

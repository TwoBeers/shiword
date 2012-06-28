<?php get_header(); ?>

<?php
	$sw_use_side = ( ( $shiword_opt['shiword_rsideb'] == 0 ) || ( ( $shiword_opt['shiword_rsideb'] == 1 ) && ( $shiword_opt['shiword_rsidebattachment'] == 0 ) ) ) ? false : true; 
	$sw_postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $sw_postswidth; ?>">

<?php if ( have_posts() ) {
	global $shiword_opt;
	while ( have_posts() ) {
		the_post(); ?>

		<div <?php post_class( 'sw-entry-standard' ) ?> id="post-<?php the_ID(); ?>">

			<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php shiword_I_like_it(); ?>
			<?php shiword_extrainfo( array( 'hiera' => 0, 'tags' => 0, 'cats' => 0 ) ); ?>

			<div class="storycontent">

				<div class="entry-attachment">

					<?php if ( wp_attachment_is_image() ) { //from twentyten WP theme
						$sw_attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
						foreach ( $sw_attachments as $sw_k => $sw_attachment ) {
							if ( $sw_attachment->ID == $post->ID )
								break;
						}
						$sw_nextk = $sw_k + 1;
						$sw_prevk = $sw_k - 1;
						?>
						<div class="img-navi">
			
						<?php if ( isset( $sw_attachments[ $sw_prevk ] ) ) { ?>
								<a class="size-thumbnail" title="" href="<?php echo get_attachment_link( $sw_attachments[ $sw_prevk ]->ID ); ?>">&laquo; <?php echo wp_get_attachment_image( $sw_attachments[ $sw_prevk ]->ID, array( 50, 50 ) ); ?></a>
						<?php } ?>
						<span class="img-navi-curimg"><?php echo wp_get_attachment_image( $post->ID, array( 50, 50 ) ); ?></span>
						<?php if ( isset( $sw_attachments[ $sw_nextk ] ) ) { ?>
								<a class="size-thumbnail" title="" href="<?php echo get_attachment_link( $sw_attachments[ $sw_nextk ]->ID ); ?>"><?php echo wp_get_attachment_image( $sw_attachments[ $sw_nextk ]->ID, array( 50, 50 ) ); ?> &raquo;</a>
						<?php } ?>
						</div>
						<p class="attachment"><a href="<?php echo wp_get_attachment_url(); ?>" title="<?php _e( 'View full size','shiword' ) ;  // link to Full size image ?>" rel="attachment"><?php
							echo wp_get_attachment_image( $post->ID, 'full' );
						?></a></p>
						<?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?>
						<?php if ( !empty( $post->post_content ) ) the_content(); ?>
					<?php } else { ?>
						<?php echo wp_get_attachment_link( $post->ID, 'thumbnail', 0, 1 ); ?> 
						<div class="entry-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>
					<?php } ?>
				</div><!-- .entry-attachment -->
			</div>
			<div class="fixfloat"> </div>
		</div>

		<?php shiword_hook_after_post(); ?>

		<?php shiword_get_sidebar( 'single' ); // show single widget area ?>

		<?php comments_template(); // Get wp-comments.php template ?>

		<?php	} //end while
	} else {?>

		<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>

	<?php } ?>
</div>

<?php if ( $sw_use_side ) shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

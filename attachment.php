<?php get_header(); ?>

<div class="posts_wide letsstick">

<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

			<div id="close_preview">
				<a href="<?php the_permalink() ?>" rel="bookmark"><?php _e( 'Close', 'shiword' ); ?></a>
				<a href="javascript:window.print()" id="print_button"><?php _e( 'Print', 'shiword' ); ?></a>
				<script type="text/javascript" defer="defer">
					document.getElementById("print_button").style.display = 'block'; // print button (available only with js active)
				</script>
			</div>

			<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php shiword_extrainfo( true, true, true, false, false ); ?>

			<div class="storycontent">

				<div class="entry-attachment" style="text-align: center;">

					<?php if ( wp_attachment_is_image() ) { //from twentyten WP theme
						$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
						foreach ( $attachments as $k => $attachment ) {
							if ( $attachment->ID == $post->ID )
								break;
						}
						$nextk = $k + 1;
						$prevk = $k - 1;
						?>
						<div class="img-navi" style="text-align: center;">
			
						<?php if ( isset( $attachments[ $prevk ] ) ) { ?>
								<a class="size-thumbnail" title="" href="<?php echo get_attachment_link( $attachments[ $prevk ]->ID ); ?>">&laquo; <?php echo wp_get_attachment_image( $attachments[ $prevk ]->ID, array( 50, 50 ) ); ?></a>
						<?php } ?>
						<span class="img-navi-curimg"><?php echo wp_get_attachment_image( $post->ID, array( 50, 50 ) ); ?></span>
						<?php if ( isset( $attachments[ $nextk ] ) ) { ?>
								<a class="size-thumbnail" title="" href="<?php echo get_attachment_link( $attachments[ $nextk ]->ID ); ?>"><?php echo wp_get_attachment_image( $attachments[ $nextk ]->ID, array( 50, 50 ) ); ?> &raquo;</a>
						<?php } ?>
						</div>
						<p class="attachment"><a href="<?php echo wp_get_attachment_url(); ?>" title="<?php _e( 'View full size','shiword' ) ;  // link to Full size image ?>" rel="attachment"><?php
							$attachment_width  = apply_filters( 'shiword_attachment_size', 1000 );
							$attachment_height = apply_filters( 'shiword_attachment_height', 1000 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_width, $attachment_height ) ); // filterable image width with, essentially, no limit for image height.
						?></a></p>
					<?php } else { ?>
						<?php if ( ! shiword_add_audio_player( '<a href="' . wp_get_attachment_url() . '">link</a>' ) ) { ?>
							<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>" rel="attachment"><?php echo wp_basename( wp_get_attachment_url() ); ?></a>
						<?php } ?>
					<?php } ?>
				</div><!-- .entry-attachment -->
				<div class="entry-caption"><?php if ( !empty( $post->post_content ) ) the_content(); ?></div>
			</div>
			<div class="fixfloat">
					<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages:', 'shiword' ) . '&after=</div><div class="fixfloat"></div>' ); ?>
			</div>
			<div class="fixfloat"> </div>
			<?php $tmptrackback = get_trackback_url(); ?>
		</div>

		<?php comments_template(); // Get wp-comments.php template ?>

		<?php	} //end while
	} else {?>

		<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>

	<?php } ?>
</div>

<?php get_footer(); ?>

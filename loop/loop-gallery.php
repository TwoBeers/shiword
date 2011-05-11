<?php global $shiword_opt; ?>
<?php $show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_gallery'] ) && $shiword_opt['shiword_postformat_gallery'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] . $sw_use_format_style ) ?> id="post-<?php the_ID(); ?>">
	<?php 
		// Post thumbnail
		if( $shiword_opt['shiword_pthumb'] ==1 ) {
			$thumbed_link = shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft' );
	?>
	<a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $thumbed_link; ?></a>
	<?php
		}
	?>
	<div class="post-body">
		<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
			$post_title = the_title( '','',false );
			if ( !$post_title ) {
				the_time( get_option( 'date_format' ) );
			} else {
				echo $post_title;
			}
			?></a>
		</h2>
		<?php if ( $show_xinfo ) { shiword_extrainfo( true, true, true, true, true ); } ?>
		<div class="storycontent">
			<?php
				$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
				if ( $images ) {
					$total_images = count( $images );
					$image = array_shift( $images );
			?>
				<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) { // compact view ?>
					<div class="gallery-thumb" style="width: <?php echo get_option('thumbnail_size_w'); ?>px;">
						<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $image->ID, 'thumbnail' ); ?></a>
					</div><!-- .gallery-thumb -->
				<?php } else { // normal view ?>
					<div class="gallery-thumb" style="width: <?php echo get_option('medium_size_w'); ?>px;">
						<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $image->ID, 'medium' ); ?></a>
					</div><!-- .gallery-thumb -->
					<?php 
						$otherimgs = array_slice( $images, 0, 4 );
						foreach ($otherimgs as $image) {
							$image_img_tag = wp_get_attachment_image( $image->ID, array( 75, 75 ) );
							?>
								<div class="gallery-thumb" style="width: <?php echo floor( get_option('thumbnail_size_w')/2 ); ?>px;">
									<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
								</div>
							<?php
						}
					?>
				<?php } ?>
				<p style="float: left; white-space: nowrap;">
					<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $total_images, 'shiword' ),
						'href="' . get_permalink() . '" title="' . __( 'View gallery', 'shiword' ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
						); ?></em>
				</p>
				<div class="fixfloat"> </div>
			<?php } ?>
			<?php the_excerpt(); ?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

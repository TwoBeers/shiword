<?php global $shiword_opt; ?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">
	<?php if( $shiword_opt['shiword_pthumb'] ==1 ) echo shiword_get_the_thumb( $post->ID, $shiword_opt['shiword_pthumb_size'], $shiword_opt['shiword_pthumb_size'], 'alignleft','', true ); // Post thumbnail ?>
	<div class="post-body">
		<?php shiword_hook_entry_top(); ?>
		
		<?php shiword_post_title( array( 'fallback' => get_the_time( get_option( 'date_format' ) ) ) ); ?>
		
		<?php shiword_extrainfo(); ?>
		<div class="storycontent">
			<?php
				$sw_images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
				if ( $sw_images ) {
					$sw_total_images = count( $sw_images );
					$sw_image = array_shift( $sw_images );
			?>
				<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) {		// compact view ?>
				
					<div class="gallery-thumb" style="width: <?php echo get_option('thumbnail_size_w'); ?>px;">
						<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $sw_image->ID, 'thumbnail' ); ?></a>
					</div><!-- .gallery-thumb -->
					<p style="float: left; white-space: nowrap;">
						<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $sw_total_images, 'shiword' ),
							'href="' . get_permalink() . '" title="' . __( 'View gallery', 'shiword' ) . '" rel="bookmark"',
							number_format_i18n( $sw_total_images )
							); ?></em>
					</p>
					
				<?php } else {																				// normal view ?>
				
					<div class="gallery-thumb" style="width: <?php echo get_option('medium_size_w'); ?>px;">
						<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $sw_image->ID, 'medium' ); ?></a>
					</div><!-- .gallery-thumb -->
					<?php 
						$sw_otherimgs = array_slice( $sw_images, 0, 4 );
						foreach ($sw_otherimgs as $sw_image) {
							$sw_image_img_tag = wp_get_attachment_image( $sw_image->ID, array( 75, 75 ) );
							?>
								<div class="gallery-thumb" style="width: <?php echo floor( get_option('thumbnail_size_w')/2 ); ?>px;">
									<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $sw_image_img_tag; ?></a>
								</div>
							<?php
						}
					?>
					<p style="float: left; white-space: nowrap;">
						<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $sw_total_images, 'shiword' ),
							'href="' . get_permalink() . '" title="' . __( 'View gallery', 'shiword' ) . '" rel="bookmark"',
							number_format_i18n( $sw_total_images )
							); ?></em>
					</p>
					<div class="fixfloat"> </div>
					<?php the_excerpt(); ?>
					
				<?php } ?>
				
			<?php } else { ?>
				<?php the_content(); ?>
			<?php } ?>
		</div>
		<?php shiword_hook_entry_bottom(); ?>
	</div>
	<div class="fixfloat"> </div>
</div>

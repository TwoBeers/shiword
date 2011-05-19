<?php global $shiword_opt; ?>
<?php $sw_show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_gallery'] ) && $shiword_opt['shiword_postformat_gallery'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] . $sw_use_format_style ) ?> id="post-<?php the_ID(); ?>">
	<?php 
		// Post thumbnail
		if( $shiword_opt['shiword_pthumb'] ==1 ) {
			$sw_thumbed_link = shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft' );
	?>
	<a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $sw_thumbed_link; ?></a>
	<?php
		}
	?>
	<div class="post-body">
		<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
			$sw_post_title = the_title( '','',false );
			if ( !$sw_post_title ) {
				the_time( get_option( 'date_format' ) );
			} else {
				echo $sw_post_title;
			}
			?></a>
		</h2>
		<?php if ( $sw_show_xinfo ) { shiword_extrainfo( true, true, true, true, true ); } ?>
		<div class="storycontent">
			<?php
				$sw_images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
				if ( $sw_images ) {
					$sw_total_images = count( $sw_images );
					$sw_image = array_shift( $sw_images );
			?>
				<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) { // compact view ?>
					<div class="gallery-thumb" style="width: <?php echo get_option('thumbnail_size_w'); ?>px;">
						<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $sw_image->ID, 'thumbnail' ); ?></a>
					</div><!-- .gallery-thumb -->
				<?php } else { // normal view ?>
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
				<?php } ?>
				<p style="float: left; white-space: nowrap;">
					<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $sw_total_images, 'shiword' ),
						'href="' . get_permalink() . '" title="' . __( 'View gallery', 'shiword' ) . '" rel="bookmark"',
						number_format_i18n( $sw_total_images )
						); ?></em>
				</p>
				<div class="fixfloat"> </div>
			<?php } ?>
			<?php the_excerpt(); ?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

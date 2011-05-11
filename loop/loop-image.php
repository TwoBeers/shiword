<?php global $shiword_opt; ?>
<?php $show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_image'] ) && $shiword_opt['shiword_postformat_image'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

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
		<?php
			$post_title = the_title( '','',false );
			
			$first_img = shiword_get_first_image();
			$def_vals = array( 'img' => '', 'title' => '', 'src' => '',);
			if ( $first_img ) {
				$first_img = array_merge( $def_vals, $first_img );
				if ( $first_img['title'] != '' )
					$post_title = $first_img['title'];
		?>
			<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $post_title; ?></a></h2>
			<?php if ( $show_xinfo ) { shiword_extrainfo( true, true, true, true, true ); } ?>
			<div class="storycontent">
				<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) { ?>
					<div class="alignleft">
						<a href="<?php echo $first_img['src']; ?>" title="<?php echo $first_img['title']; ?>">
							<img style="max-height: <?php echo get_option('thumbnail_size_w'); ?>px; max-width: <?php echo get_option('medium_size_h'); ?>px;" title="<?php echo $first_img['title']; ?>" src="<?php echo $first_img['src']; ?>" />
						</a>
					</div>
					<?php the_title(); ?>
				<?php } else { ?>
					<a href="<?php echo $first_img['src']; ?>" title="<?php echo $first_img['title']; ?>"><?php echo $first_img['img']; ?></a>
					<br />
					<?php the_excerpt(); ?>
				<?php } ?>
			</div>
		<?php 
			}
		?>
	</div>
	<div class="fixfloat"> </div>
</div>

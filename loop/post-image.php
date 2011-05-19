<?php global $shiword_opt; ?>
<?php $sw_show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_image'] ) && $shiword_opt['shiword_postformat_image'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

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
		<?php
			$sw_post_title = the_title( '','',false );
			
			$sw_first_img = shiword_get_first_image();
			$sw_def_vals = array( 'img' => '', 'title' => '', 'src' => '',);
			if ( $sw_first_img ) {
				$sw_first_img = array_merge( $sw_def_vals, $sw_first_img );
				if ( $sw_first_img['title'] != '' )
					$sw_post_title = $sw_first_img['title'];
		?>
			<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $sw_post_title; ?></a></h2>
			<?php if ( $sw_show_xinfo ) { shiword_extrainfo( true, true, true, true, true ); } ?>
			<div class="storycontent">
				<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) { ?>
					<div class="alignleft">
						<a href="<?php echo $sw_first_img['src']; ?>" title="<?php echo $sw_first_img['title']; ?>">
							<img style="max-height: <?php echo get_option('medium_size_w'); ?>px; max-width: <?php echo get_option('medium_size_h'); ?>px;" title="<?php echo $sw_first_img['title']; ?>" src="<?php echo $sw_first_img['src']; ?>" />
						</a>
					</div>
					<?php the_title(); ?>
				<?php } else { ?>
					<a href="<?php echo $sw_first_img['src']; ?>" title="<?php echo $sw_first_img['title']; ?>"><?php echo $sw_first_img['img']; ?></a>
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

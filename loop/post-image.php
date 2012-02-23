<?php global $shiword_opt; ?>

<?php
	$sw_first_img = shiword_get_first_image();
?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">
	<?php if( $shiword_opt['shiword_pthumb'] ==1 ) echo shiword_get_the_thumb( $post->ID, $shiword_opt['shiword_pthumb_size'], $shiword_opt['shiword_pthumb_size'], 'alignleft','', true ); // Post thumbnail ?>
	<div class="post-body">
		<?php shiword_hook_before_post_title(); ?>
		<?php shiword_post_title( array( 'alternative' => $sw_first_img ? $sw_first_img['title'] : '', 'fallback' => get_the_time( get_option( 'date_format' ) ) ) ); ?>
		<?php shiword_hook_after_post_title(); ?>
		<?php shiword_extrainfo(); ?>
		<div class="storycontent">
		
		<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) { ?>
			<?php if ( $sw_first_img ) { ?>
				<a href="<?php echo $sw_first_img['src']; ?>" title="<?php echo $sw_first_img['title']; ?>"><img style="max-height: <?php echo get_option('medium_size_w'); ?>px; max-width: <?php echo get_option('medium_size_h'); ?>px;" title="<?php echo $sw_first_img['title']; ?>" src="<?php echo $sw_first_img['src']; ?>" /></a>
			<?php } else { ?>
				<?php the_excerpt(); ?>
			<?php } ?>
		<?php } else { ?>
			<?php if ( $sw_first_img ) { ?>
				<a href="<?php echo $sw_first_img['src']; ?>" title="<?php echo $sw_first_img['title']; ?>"><?php echo $sw_first_img['img']; ?></a>
			<?php } else { ?>
				<?php the_content(); ?>
			<?php } ?>
		<?php } ?>
		
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

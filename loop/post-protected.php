<?php global $shiword_opt; ?>

<div <?php post_class( 'sw-entry-standard' ) ?> id="post-<?php the_ID(); ?>">
	<?php if( $shiword_opt['shiword_pthumb'] ==1 ) {?><img class="alignleft wp-post-image" alt="thumb" src="<?php echo get_template_directory_uri(); ?>/images/thumbs/lock.png" /><?php } ?>
	<div class="post-body">
		<?php shiword_post_title( array( 'fallback' => get_the_time( get_option( 'date_format' ) ) ) ); ?>
		<div class="storycontent">
			<?php the_content(); ?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

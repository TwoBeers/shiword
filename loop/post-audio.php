<?php global $shiword_opt; ?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">
	<?php if( $shiword_opt['shiword_pthumb'] ==1 ) echo shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft','', true ); // Post thumbnail ?>
	<div class="post-body">
		<?php shiword_hook_before_post_title(); ?>
		<?php shiword_post_title( array( 'fallback' => get_the_time( get_option( 'date_format' ) ) ) ); ?>
		<?php shiword_hook_after_post_title(); ?>
		<?php shiword_extrainfo(); ?>
		<div class="storycontent">
			<?php shiword_add_audio_player(); ?>
			<?php if ( ( $shiword_opt['shiword_xcont'] == 0 ) && !is_archive() && !is_search() ) { // normal view ?>
				<?php the_content(); ?>
			<?php } ?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

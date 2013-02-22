<?php global $shiword_opt; ?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">
	<?php if( $shiword_opt['shiword_pthumb'] ==1 ) echo shiword_get_the_thumb( $post->ID, $shiword_opt['shiword_pthumb_size'], $shiword_opt['shiword_pthumb_size'], 'alignleft','', true ); // Post thumbnail ?>
	<div class="post-body">

		<?php shiword_hook_entry_top(); ?>

		<?php shiword_post_title( array( 'fallback' => get_the_time( get_option( 'date_format' ) ) ) ); ?>

		<?php shiword_extrainfo(); ?>

		<div class="storycontent">
			<?php if ( ( $shiword_opt['shiword_xcont'] == 0 ) && !is_archive() && !is_search() ) { // normal view ?>
				<?php the_content(); ?>
			<?php } ?>
		</div>

		<?php shiword_hook_entry_bottom(); ?>

	</div>
	<div class="fixfloat"> </div>
</div>

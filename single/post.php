<div <?php post_class( 'sw-entry-standard' ) ?> id="post-<?php the_ID(); ?>">
	<?php shiword_hook_before_post_title(); ?>
	<?php shiword_post_title( array( 'fallback' => get_the_time( get_option( 'date_format' ) ), 'featured' => 1 ) ); ?>
	<?php shiword_hook_after_post_title(); ?>
	<?php shiword_I_like_it(); ?>
	<?php shiword_extrainfo(); ?>
	<div class="storycontent">
		<?php the_content(); ?>
	</div>
	<div class="fixfloat">
		<?php wp_link_pages( 'before=<div class="meta comment_tools" style="text-align: right;">' . __( 'Pages:', 'shiword' ) . '&after=</div><div class="fixfloat"></div>' ); ?>
	</div>
	<div class="fixfloat"> </div>
</div>
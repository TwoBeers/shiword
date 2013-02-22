<div <?php post_class( 'sw-entry-standard' ) ?> id="post-<?php the_ID(); ?>">
	<?php shiword_hook_entry_top(); ?>
	
	<?php shiword_post_title( array( 'fallback' => get_the_time( get_option( 'date_format' ) ), 'featured' => 1 ) ); ?>
	
	<?php shiword_I_like_it(); ?>
	<?php shiword_extrainfo(); ?>
	<div class="storycontent entry-content">
		<?php the_content(); ?>
	</div>
	<div class="fixfloat">
		<?php wp_link_pages( 'before=<div class="meta sw-paginated-entry">' . __( 'Pages', 'shiword' ) . ':&after=</div>' ); ?>
	</div>
	<?php shiword_hook_entry_bottom(); ?>
</div>
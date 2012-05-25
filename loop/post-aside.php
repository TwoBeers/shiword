<?php global $shiword_opt; ?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">
	<div class="post-body">
		<div class="storycontent">
			<?php the_content(); ?>
			<div class="fixfloat sw-aside-info"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a> - <?php comments_popup_link('(0)', '(1)','(%)'); ?><?php edit_post_link( __( 'Edit', 'shiword' ),' - ' ); ?></div>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

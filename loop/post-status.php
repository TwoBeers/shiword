<?php global $shiword_opt; ?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">
	<div class="sw-status-image">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_avatar( $post->post_author, 50, $default=get_option('avatar_default'), get_the_author() ); ?></a>
	</div>
	<div class="post-body">
		<?php shiword_hook_entry_top(); ?>
		<div class="storycontent">
			<div class="sw-status-author"><?php printf( '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( 'View all posts by %s', esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' ); ?><?php edit_post_link( __( 'Edit', 'shiword' ),' - ' ); ?></div>
			<?php the_content(); ?>
			<div class="fixfloat sw-status-date"><?php echo shiword_friendly_date(); ?> </div>
		</div>
		<?php shiword_hook_entry_bottom(); ?>
	</div>
	<div class="fixfloat"> </div>
</div>
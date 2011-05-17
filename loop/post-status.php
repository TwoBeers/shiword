<?php global $shiword_opt; ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_status'] ) && $shiword_opt['shiword_postformat_status'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] . $sw_use_format_style ) ?> id="post-<?php the_ID(); ?>">
	<div class="status-image">
		<?php echo get_avatar( $post->post_author, 50, $default=get_option('avatar_default'), get_the_author() ); ?>
	</div>
	<div class="post-body">
		<div class="storycontent">
			<span style="font-size: 11px; font-weight: bold; color: #404040;"><?php printf( '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( 'View all posts by %s', esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' ); ?><?php edit_post_link( __( 'Edit', 'shiword' ),' - ' ); ?></span>
			<?php the_content(); ?>
			<span style="font-size: 11px; color: #404040;"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?> </span>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>
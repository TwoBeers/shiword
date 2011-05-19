<?php global $shiword_opt; ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_aside'] ) && $shiword_opt['shiword_postformat_aside'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] . $sw_use_format_style ) ?> id="post-<?php the_ID(); ?>">
	<div class="status-image">
		<?php
			// Post thumbnail
			if( $shiword_opt['shiword_pthumb'] ==1 ) {
				$sw_thumbed_link = shiword_get_the_thumb( $post->ID, 50, 50, '' );
		?>
		<a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $sw_thumbed_link; ?></a>
		<?php
			}
		?>
	</div>
	<div class="post-body">
		<div class="storycontent">
			<?php the_content(); ?>
			<span class="fixfloat" style="font-size: 11px; font-style: italic; color: #404040;"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a> - <?php comments_popup_link('(0)', '(1)','(%)'); ?><?php edit_post_link( __( 'Edit', 'shiword' ),' - ' ); ?></span>
		</div>
	</div>
</div>

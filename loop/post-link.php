<?php global $shiword_opt; ?>

<?php
	$sw_first_link = shiword_get_first_link();
?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">
	<?php if( $shiword_opt['shiword_pthumb'] ==1 ) echo shiword_get_the_thumb( $post->ID, $shiword_opt['shiword_pthumb_size'], $shiword_opt['shiword_pthumb_size'], 'alignleft','', true ); // Post thumbnail ?>
	<div class="post-body">
		<?php shiword_hook_before_post_title(); ?>
		<?php shiword_post_title( $sw_first_link ? array( 'alternative' => $sw_first_link['text'] , 'title' => $sw_first_link['text'], 'extra' => '<a href="' . $sw_first_link['href'] . '" rel="bookmark"><img class="h2-ext-link" alt="link" src="' . get_template_directory_uri() . '/images/link.png" /></a> - ' ) : array( 'fallback' => get_the_time( get_option( 'date_format' ) ) ) ) ; ?>
		<?php shiword_hook_after_post_title(); ?>
		<?php shiword_extrainfo( array( 'auth' => 0 ) ); ?>
		<div class="storycontent">
			<?php the_excerpt(); ?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

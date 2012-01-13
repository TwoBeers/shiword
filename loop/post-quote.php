<?php global $shiword_opt; ?>

<?php 
	$sw_first_quote = shiword_get_blockquote();
	$sw_auth = ( !$sw_first_quote || $sw_first_quote['cite'] == '' ) ? 0 : $sw_first_quote['cite'];
?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">
	<?php if( $shiword_opt['shiword_pthumb'] ==1 ) echo shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft','', true ); // Post thumbnail ?>
	<div class="post-body">
		<?php shiword_hook_before_post_title(); ?>
		<?php shiword_post_title( array( 'alternative' => $sw_first_quote['quote'] ? '&#8220;' . $sw_first_quote['quote'] . '&#8221;' : '', 'fallback' => get_the_time( get_option( 'date_format' ) ) ) ); ?>
		<?php shiword_hook_after_post_title(); ?>
		<?php shiword_extrainfo( array( 'auth' => $sw_auth ) ); ?>
		<div class="storycontent">
			<?php if ( ( $shiword_opt['shiword_xcont'] == 0 ) && !is_archive() && !is_search() ) the_content(); ?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

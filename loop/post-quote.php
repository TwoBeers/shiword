<?php global $shiword_opt; ?>
<?php $sw_show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_quote'] ) && $shiword_opt['shiword_postformat_quote'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] . $sw_use_format_style ) ?> id="post-<?php the_ID(); ?>">
	<?php
		// Post thumbnail
		if( $shiword_opt['shiword_pthumb'] ==1 ) {
			$sw_thumbed_link = shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft' );
	?>
	<a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $sw_thumbed_link; ?></a>
	<?php
		}
	?>
	<div class="post-body">
		<?php
			$sw_first_quote = shiword_get_blockquote();
			$sw_post_title = the_title( '','',false );
			if ( $sw_first_quote['quote'] ) {
		?>
		<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo '&#8220;' . $sw_first_quote['quote'] . '&#8221;'; ?></a></h2>
		<?php $sw_auth = ( $sw_first_quote['cite'] == '' ) ? false : $sw_first_quote['cite']; ?>
		<?php if ( $sw_show_xinfo ) { shiword_extrainfo( $sw_auth, true, true, true, true ); } ?>
		<div class="storycontent">
			<?php if ( ( $shiword_opt['shiword_xcont'] == 0 ) && !is_archive() && !is_search() ) the_content(); ?>
		</div>
		<?php
			}
		?>
	</div>
	<div class="fixfloat"> </div>
</div>

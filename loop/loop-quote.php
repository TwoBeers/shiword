<?php global $shiword_opt; ?>
<?php $show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_quote'] ) && $shiword_opt['shiword_postformat_quote'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] . $sw_use_format_style ) ?> id="post-<?php the_ID(); ?>">
	<?php
		// Post thumbnail
		if( $shiword_opt['shiword_pthumb'] ==1 ) {
			$thumbed_link = shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft' );
	?>
	<a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $thumbed_link; ?></a>
	<?php
		}
	?>
	<div class="post-body">
		<?php
			$first_quote = shiword_get_blockquote();
			$post_title = the_title( '','',false );
			if ( $first_quote['quote'] ) {
		?>
		<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo '&#8220;' . $first_quote['quote'] . '&#8221;'; ?></a></h2>
		<?php $auth = ( $first_quote['cite'] == '' ) ? false : $first_quote['cite']; ?>
		<?php if ( $show_xinfo ) { shiword_extrainfo( $auth, true, true, true, true ); } ?>
		<div class="storycontent">
			<?php if ( ( $shiword_opt['shiword_xcont'] == 0 ) && !is_archive() && !is_search() ) the_content(); ?>
		</div>
		<?php
			}
		?>
	</div>
	<div class="fixfloat"> </div>
</div>

<?php global $shiword_opt; ?>
<?php $show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_audio'] ) && $shiword_opt['shiword_postformat_audio'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

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
		<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
			$post_title = the_title( '','',false );
			if ( !$post_title ) {
				_e( '(no title)', 'shiword' );
			} else {
				echo $post_title;
			}
			?></a>
		</h2>
		<?php if ( $show_xinfo ) { shiword_extrainfo( false, true, true, true, true ); } ?>
		<div class="storycontent">
			<?php shiword_add_audio_player(); ?>
			<?php if ( ( $shiword_opt['shiword_xcont'] == 0 ) && !is_archive() && !is_search() ) { // normal view ?>
				<?php the_content(); ?>
			<?php } ?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

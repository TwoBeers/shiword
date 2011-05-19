<?php global $shiword_opt; ?>
<?php $sw_show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>

<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] ) ?> id="post-<?php the_ID(); ?>">
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
		<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
			$sw_post_title = the_title( '','',false );
			if ( !$sw_post_title ) {
				_e( '(no title)', 'shiword' );
			} else {
				echo $sw_post_title;
			}
			?></a>
		</h2>
		<?php if ( $sw_show_xinfo ) { shiword_extrainfo( true, true, true, true, true ); } ?>
		<div class="storycontent">
			<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) 
				the_excerpt();
			else
				the_content();
			?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

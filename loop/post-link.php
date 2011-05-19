<?php global $shiword_opt; ?>
<?php $sw_show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_link'] ) && $shiword_opt['shiword_postformat_link'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

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
			$sw_post_title = the_title( '','',false );
			
			$sw_first_link = shiword_get_first_link();
			$sw_def_vals = array( 'anchor' => '', 'title' => '', 'href' => '',);
			if ( $sw_first_link ) {
				$sw_first_link = array_merge( $sw_def_vals, $sw_first_link );
				if ( $sw_first_link['title'] != '' )
					$sw_post_title = $sw_first_link['title'];
		?>
			<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $sw_post_title; ?></a> - <a href="<?php echo $sw_first_link['href']; ?>" rel="bookmark"><img class="h2-ext-link" alt="link" src="<?php echo get_stylesheet_directory_uri() . '/images/link.png'; ?>" /></a></h2>
			<?php if ( $sw_show_xinfo ) { shiword_extrainfo( false, true, true, true, true ); } ?>
		<?php 
			} 
		?>
	</div>
	<div class="fixfloat"> </div>
</div>

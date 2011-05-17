<?php global $shiword_opt; ?>
<?php $show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ; // show extra info ?>
<?php $sw_use_format_style = ( isset( $shiword_opt['shiword_postformat_link'] ) && $shiword_opt['shiword_postformat_link'] == 1 ) ? ' sw-use-format-style' : '' ; ?>

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
			$post_title = the_title( '','',false );
			
			$first_link = shiword_get_first_link();
			$def_vals = array( 'anchor' => '', 'title' => '', 'href' => '',);
			if ( $first_link ) {
				$first_link = array_merge( $def_vals, $first_link );
				if ( $first_link['title'] != '' )
					$post_title = $first_link['title'];
		?>
			<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $post_title; ?></a> - <a href="<?php echo $first_link['href']; ?>" rel="bookmark"><img class="h2-ext-link" alt="link" src="<?php echo get_stylesheet_directory_uri() . '/images/link.png'; ?>" /></a></h2>
			<?php if ( $show_xinfo ) { shiword_extrainfo( false, true, true, true, true ); } ?>
		<?php 
			} 
		?>
	</div>
	<div class="fixfloat"> </div>
</div>

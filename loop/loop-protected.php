<?php global $shiword_opt; ?>

<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] ) ?> id="post-<?php the_ID(); ?>">
	<?php if( $shiword_opt['shiword_pthumb'] ==1 ) {?><img class="alignleft wp-post-image" alt="thumb" src="<?php echo get_template_directory_uri(); ?>/images/thumbs/lock.png" /><?php } ?>
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
		<div class="storycontent">
			<?php the_content(); ?>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>

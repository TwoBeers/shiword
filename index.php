<?php get_header(); ?>
<?php 
	global $shiword_opt, $query_string;
 
	// search reminder
	if ( is_category() ) {
		echo '<div class="wp-caption aligncenter"><p class="wp-caption-text"><strong>' . __( 'Category', 'shiword' ) . ': ';
		wp_title( '',true,'right' );
		echo ' </strong></p></div>'; 
	}
	elseif ( is_tag() ) {
		echo '<div class="wp-caption aligncenter"><p class="wp-caption-text"><strong>' . __( 'Tag', 'shiword' ) . ': ';
		wp_title( '',true,'right' );
		echo ' </strong></p></div>'; 
	}
	elseif ( is_date() ) {
		echo '<div class="wp-caption aligncenter"><p class="wp-caption-text"><strong>' . __( 'Archives', 'shiword' ) . ': ';
		wp_title( '',true,'right' );
		echo ' </strong></p></div>'; 
	}
	elseif ( is_search() ) {
		printf( '<div class="wp-caption aligncenter"><p class="wp-caption-text">' . __( 'Search results for &#8220;%s&#8221;', 'shiword' ) . '</p></div>', '<strong>' . esc_html( get_search_query() ) . '</strong>' );
	}
	elseif (is_author()) {
		the_post();	?>
		<div class="wp-caption aligncenter">
			<?php printf( '<p class="wp-caption-text">' . __( 'Posts by %s', 'shiword' ) . '</p>', '<strong>' . get_the_author() . '</strong>' );
			// If a user has filled out their description, show a bio on their entries.
			if ( get_the_author_meta( 'description' ) ) { ?>
				<div id="entry-author-info">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 50, $default= get_template_directory_uri() . '/images/user.png','user-avatar' ); ?>
					<span><?php printf( __( 'About %s', 'shiword' ), get_the_author() ); ?></span>
					<br />
					<?php the_author_meta( 'description' ); ?>
					<div class="fixfloat" ></div>
				</div><!-- #entry-author-info -->
			<?php } ?>
		</div>
		<?php rewind_posts();
	}

	// show extra info
	$show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ;
?>
<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] ) ?> id="post-<?php the_ID(); ?>">
			<?php
			// Post thumbnail
				if( $shiword_opt['shiword_pthumb'] ==1 ) {
					if( has_post_thumbnail() ) {
						$thumbed_link = get_the_post_thumbnail( $post->ID, array( 120,120 ), array( 'class' => 'alignleft' ) );
					} else {
						if ( function_exists( 'get_post_format' ) && get_post_format() ) {
							$format = get_post_format();
						} else {
							$format = 'thumb';
						}
						$thumbed_link = '<img class="alignleft wp-post-image" alt="thumb" src="' . get_template_directory_uri() . '/images/thumbs/' . $format . '.png" />';
					}
					?><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $thumbed_link; ?></a><?php
				}
			?>
			<div class="post-body">
<?php // display posts of the Gallery format ?>
				<?php if ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) { ?>
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
						$post_title = the_title( '','',false );
						if ( !$post_title ) {
							the_time( get_option( 'date_format' ) );
						} else {
							echo $post_title;
						}
						?></a>
					</h2>
					<?php if ( $show_xinfo ) { shiword_extrainfo( true, true, true, true, true ); } ?>
					<div class="storycontent">
						<?php if ( post_password_required() ) { ?>
							<?php the_content(); ?>
						<?php } else { ?>
							<?php
								$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
								if ( $images ) {
									$total_images = count( $images );
									$image = array_shift( $images );
									$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
							?>
									<div class="gallery-thumb">
										<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
									</div><!-- .gallery-thumb -->
									<p style="text-align: center;"><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s image</a>.', 'This gallery contains <a %1$s>%2$s images</a>.', $total_images, 'shiword' ),
											'href="' . get_permalink() . '" title="' . __( 'View gallery', 'shiword' ) . '" rel="bookmark"',
											number_format_i18n( $total_images )
										); ?></em></p>
							<?php } ?>
							<?php the_excerpt(); ?>
						<?php } ?>
					</div>
<?php // display posts of the Aside format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'aside' == get_post_format( $post->ID ) ) { ?>
					<div class="storycontent">
						<?php the_content(); ?>
						<span style="font-size: 11px; font-style: italic; color: #404040;"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php comments_popup_link('(0)', '(1)','(%)'); ?></span>
					</div>
<?php // display posts of the Image format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'image' == get_post_format( $post->ID ) ) { ?>
					<?php
						$post_title = the_title( '','',false );
						
						$first_img = shiword_get_first_image();
						$def_vals = array( 'img' => '', 'title' => '', 'src' => '',);
						if ( $first_img ) {
							$first_img = array_merge( $def_vals, $first_img );
							if ( $first_img['title'] != '' )
								$post_title = $first_img['title'];
					?>
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $post_title; ?></a></h2>
					<?php if ( $show_xinfo ) { shiword_extrainfo( true, true, true, true, true ); } ?>
					<div class="storycontent">
						<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_category() || is_tag() || is_date() || is_search() || is_author() ) { ?>
						<div style="width:150px;">
							<a href="<?php echo $first_img['src']; ?>" title="<?php echo $first_img['title']; ?>"><?php echo $first_img['img']; ?></a>
						</div>
						<?php } else { ?>
							<a href="<?php echo $first_img['src']; ?>" title="<?php echo $first_img['title']; ?>"><?php echo $first_img['img']; ?></a>
						<?php } ?>
					</div>
					<?php 
						} 
					?>
<?php // display posts of the Link format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'link' == get_post_format( $post->ID ) ) { ?>
					<?php
						$post_title = the_title( '','',false );
						
						$first_link = shiword_get_first_link();
						$def_vals = array( 'anchor' => '', 'title' => '', 'href' => '',);
						if ( $first_link ) {
							$first_link = array_merge( $def_vals, $first_link );
							if ( $first_link['title'] != '' )
								$post_title = $first_link['title'];
					?>
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $post_title; ?></a> - <a href="<?php echo $first_link['href']; ?>" rel="bookmark"><img class="h2-ext-link" alt="link" src="<?php echo get_template_directory_uri() . '/images/link.png'; ?>" /></a></h2>
					<?php if ( $show_xinfo ) { shiword_extrainfo( false, true, true, true, true ); } ?>
					<?php 
						} 
					?>
<?php // display posts of the Quote format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'quote' == get_post_format( $post->ID ) ) { ?>
					<?php
						$first_quote = shiword_get_blockquote();
						$post_title = the_title( '','',false );
						if ( $first_quote ) {
					?>
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $first_quote; ?></a></h2>
					<?php if ( $show_xinfo ) { shiword_extrainfo( false, true, true, true, true ); } ?>
					<div class="storycontent">
						<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_category() || is_tag() || is_date() || is_search() || is_author() ) 
							echo $post_title;
						else
							the_content();
						?>
					</div>
					<?php
						}
					?>
<?php // display posts of the other formats ?>
				<?php } else { ?>
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
						$post_title = the_title( '','',false );
						if ( !$post_title ) {
							_e( '(no title)', 'shiword' );
						} else {
							echo $post_title;
						}
						?></a>
					</h2>
					<?php if ( $show_xinfo ) { shiword_extrainfo( true, true, true, true, true ); } ?>
					<div class="storycontent">
						<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_category() || is_tag() || is_date() || is_search() || is_author() ) 
							the_excerpt();
						else
							the_content();
						?>
					</div>
				<?php } ?>
			</div>

			<div class="fixfloat"> </div>
		</div>
	<?php } ?>
	<div class="w_title" style="border-bottom:none;">
		<?php //num of pages
			global $paged;
			if ( !$paged ) { $paged = 1; }
			if ( $shiword_opt['shiword_navlinks'] == 1 ) { previous_posts_link( '&laquo; '. __( 'Newer Posts', 'shiword' ) . ' - ' ); }
			printf( __( 'page %1$s of %2$s', 'shiword' ), $paged, $wp_query->max_num_pages );
			if ( $shiword_opt['shiword_navlinks'] == 1 ) { next_posts_link( ' - ' . __( 'Older Posts', 'shiword' ) . ' &raquo;'); }
		?>
	</div>
<?php } else { ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>
<?php } ?>
<?php get_footer(); ?>

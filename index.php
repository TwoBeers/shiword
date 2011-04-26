<?php get_header(); ?>
<?php 
	global $shiword_opt, $query_string;

	// search reminder
	if ( is_archive() ) { ?>
		<div class="meta">
			<p style="text-align: center;">
			<?php 
				if ( is_category() )	{ $strtype = __( 'Category', 'shiword' ) . ' : %s'; }
				elseif ( is_tag() )		{ $strtype = __( 'Tag', 'shiword' ) . ' : %s'; }
				elseif ( is_date() )	{ $strtype = __( 'Archives', 'shiword' ) . ' : %s'; }
				elseif (is_author()) 	{ $strtype = __( 'Posts by %s', 'shiword') ; }
			?>
			<?php printf( $strtype, '<strong style="font-size: 15px; color: #fff;">' . wp_title( '',false ) . '</strong>'); ?>
			</p>
			<?php
			if (is_author()) {
				$author = get_queried_object();
				// If a user has filled out their description, show a bio on their entries.
				if ( $author->description ) { ?>
					<div id="entry-author-info">
						<?php echo get_avatar( $author->user_email, 32, $default= get_template_directory_uri() . '/images/user.png','user-avatar' ); ?>
						<?php
							if ( $author->twitter ) echo '<a title="' . sprintf( __('follow %s on Twitter', 'shiword'), $author->display_name ) . '" href="'.$author->twitter.'"><img alt="twitter" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Twitter.png" /></a>';
							if ( $author->facebook ) echo '<a title="' . sprintf( __('follow %s on Facebook', 'shiword'), $author->display_name ) . '" href="'.$author->facebook.'"><img alt="facebook" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Facebook.png" /></a>';
						?>
						<?php echo $author->description; ?>
						<div class="fixfloat" ></div>
					</div><!-- #entry-author-info -->
				<?php }
			} ?>
		</div>
	<?php } elseif ( is_search() ) { ?>
		<div class="meta">
			<p style="text-align: center;">
			<?php printf( __( 'Search results for &#8220;%s&#8221;', 'shiword' ), '<strong style="font-size: 15px; color: #fff;">' . esc_html( get_search_query() ) . '</strong>' ); ?>
			</p>
		</div>
	<?php }

	// show extra info
	$show_xinfo = ( isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) ? 0 : 1 ;
?>
<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		<?php
			$sw_use_format_style = ( function_exists( 'get_post_format' ) && isset( $shiword_opt['shiword_postformat_' . get_post_format( $post->ID ) ] ) && $shiword_opt['shiword_postformat_' . get_post_format( $post->ID ) ] == 1 ) ? ' sw-use-format-style' : '' ;
		?>
		<div <?php post_class( 'sw-pthumb-'.$shiword_opt['shiword_pthumb'] . $sw_use_format_style ) ?> id="post-<?php the_ID(); ?>">
			<?php if ( ! post_password_required() ) {
				// Post thumbnail
				if( $shiword_opt['shiword_pthumb'] ==1 ) {
					$thumbed_link = shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft' );
			?>
			<a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $thumbed_link; ?></a>
			<?php
				}
			?>
			<div class="post-body">
<?php // display posts of the Gallery format ?>
				<?php if ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) && $shiword_opt['shiword_postformat_gallery'] == 1 ) { ?>
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
						<?php
							$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
							if ( $images ) {
								$total_images = count( $images );
								$image = array_shift( $images );
						?>
							<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) { ?>
								<div class="gallery-thumb" style="width: <?php echo get_option('thumbnail_size_w'); ?>px;">
									<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $image->ID, 'thumbnail' ); ?></a>
								</div><!-- .gallery-thumb -->
							<?php } else { ?>
								<div class="gallery-thumb" style="width: <?php echo get_option('medium_size_w'); ?>px;">
									<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $image->ID, 'medium' ); ?></a>
								</div><!-- .gallery-thumb -->
								<?php 
									$otherimgs = array_slice( $images, 0, 4 );
									foreach ($otherimgs as $image) {
										$image_img_tag = wp_get_attachment_image( $image->ID, array( 75, 75 ) );
										?>
											<div class="gallery-thumb" style="width: <?php echo floor( get_option('thumbnail_size_w')/2 ); ?>px;">
												<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
											</div><!-- .gallery-thumb -->
										<?php
									}
								?>
							<?php } ?>
							<p style="float: left; white-space: nowrap;">
								<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $total_images, 'shiword' ),
									'href="' . get_permalink() . '" title="' . __( 'View gallery', 'shiword' ) . '" rel="bookmark"',
									number_format_i18n( $total_images )
									); ?></em>
							</p>
							<div class="fixfloat"> </div>
						<?php } ?>
						<?php the_excerpt(); ?>
					</div>
<?php // display posts of the Aside format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'aside' == get_post_format( $post->ID ) && $shiword_opt['shiword_postformat_aside'] == 1 ) { ?>
					<div class="storycontent">
						<?php the_content(); ?>
						<span style="font-size: 11px; font-style: italic; color: #404040;"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php comments_popup_link('(0)', '(1)','(%)'); ?><?php edit_post_link( __( 'Edit', 'shiword' ),' - ' ); ?></span>
					</div>
<?php // display posts of the Image format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'image' == get_post_format( $post->ID ) && $shiword_opt['shiword_postformat_image'] == 1 ) { ?>
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
						<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) { ?>
							<div class="alignleft">
								<a href="<?php echo $first_img['src']; ?>" title="<?php echo $first_img['title']; ?>">
									<img style="max-height: <?php echo get_option('thumbnail_size_w'); ?>px; max-width: <?php echo get_option('medium_size_h'); ?>px;" title="<?php echo $first_img['title']; ?>" src="<?php echo $first_img['src']; ?>" />
								</a>
							</div>
							<?php the_title(); ?>
						<?php } else { ?>
							<a href="<?php echo $first_img['src']; ?>" title="<?php echo $first_img['title']; ?>"><?php echo $first_img['img']; ?></a>
							<br />
							<?php the_excerpt(); ?>
						<?php } ?>
					</div>
					<?php 
						} 
					?>
<?php // display posts of the Link format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'link' == get_post_format( $post->ID ) && $shiword_opt['shiword_postformat_link'] == 1 ) { ?>
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
<?php // display posts of the Quote format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'quote' == get_post_format( $post->ID ) && $shiword_opt['shiword_postformat_quote'] == 1 ) { ?>
					<?php
						$first_quote = shiword_get_blockquote();
						$post_title = the_title( '','',false );
						if ( $first_quote ) {
					?>
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo $first_quote; ?></a></h2>
					<?php if ( $show_xinfo ) { shiword_extrainfo( false, true, true, true, true ); } ?>
					<div class="storycontent">
						<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) 
							echo $post_title;
						else
							the_content();
						?>
					</div>
					<?php
						}
					?>
<?php // display posts of the Status format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'status' == get_post_format( $post->ID ) && $shiword_opt['shiword_postformat_status'] == 1 ) { ?>
					<div class="storycontent">
						<span style="font-size: 11px; font-style: italic; color: #404040;"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( get_option( 'date_format' ) ); ?></a><?php edit_post_link( __( 'Edit', 'shiword' ),' - ' ); ?></span>
						<?php the_content(); ?>
					</div>
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
						<?php if ( ( $shiword_opt['shiword_xcont'] == 1 ) || is_archive() || is_search() ) 
							the_excerpt();
						else
							the_content();
						?>
					</div>
				<?php } ?>
			</div>

			<div class="fixfloat"> </div>
		<?php } else {?>
			<?php if( $shiword_opt['shiword_pthumb'] ==1 ) {?><img class="alignleft wp-post-image" alt="thumb" src="<?php echo get_template_directory_uri(); ?>/images/thumbs/lock.png" /><?php } ?>
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
		<?php } ?>
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

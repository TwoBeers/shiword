<?php
/**
 * The fixed footer
 *
 * @package Shiword
 * @since Shiword 3.0
 */


/**
 * the fixed footer containers
 *
 */
if ( !function_exists( 'shiword_fixed_footer' ) ) {
	function shiword_fixed_footer() {
		global $shiword_is_printpreview;
		
		if ( $shiword_is_printpreview ) return; // useless in print preview
?>

<!-- begin fixed footer -->
<div id="fixedfoot_cont">
	<div id="fixedfoot_bg">
		<div id="fixedfoot" class="pad_bg">
			<div id="fixedfoot_overlay">
				<?php shiword_qbar() ?>
				<?php shiword_statusbar() ?>
				<?php shiword_navbuttons() ?>
			</div>
		</div>
	</div>
	<div id="fixedfoot_reflect"></div>
</div>

<!-- end fixed footer -->
<?php
	}
}


/**
 * the quickbar
 *
 */
if ( !function_exists( 'shiword_qbar' ) ) {
	function shiword_qbar(){
		global $shiword_opt, $current_user;
		
		if ( $shiword_opt['shiword_qbar'] == 0 ) return;

?>

<!-- begin quickbar -->
<div id="quickbar">

<?php

/* custom elements can be easily added to quickbar using filters. eg:

	add_filter( 'shiword_qbar_elements', 'shiword_add_my_element' );

	function shiword_add_my_element( $elements ) {
		$elements['my-first-element'] = array(
			'title' => 'my title',
			'image' => 'http://www.my-site.net/wp-content/uploads/2012/06/my-50x50-image.jpg',
			'content' => 'this is the content of my custom element. Hurray!'
		);
		return $elements;
	}

*/
	$elements = array();
	
	$elements = apply_filters('shiword_qbar_elements', $elements);
	
	foreach ( $elements as $key => $element ) {
?>

		<div class="menuitem <?php echo $key; ?>">
			<div class="menuitem_img" style="background-image:url(<?php echo $element['image'] ?>)"></div>
			<div class="menuback custom-element">
				<div class="menu_sx">
					<div class="mentit"><?php echo $element['title'] ?></div>
					<div>
						<?php echo $element['content'] ?>
					</div>
				</div>
			</div>
		</div>

<?php
	}
?>

	<?php if ( $shiword_opt['shiword_qbar_recpost'] == 1 ) { // recent posts menu ?>
		<div class="menuitem">
			<div class="menuitem_img mii_rpost"></div>
			<div class="menuback">
				<div class="menu_sx">
					<div class="mentit"><?php _e( 'Recent Posts', 'shiword' ); ?></div>
					<ul class="solid_ul">
						<?php shiword_get_recententries() ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $shiword_opt['shiword_qbar_cat'] == 1 ) { // popular categories menu ?>
		<div class="menuitem">
			<div  class="menuitem_img mii_pcats"></div>
			<div class="menuback">
				<div class="menu_sx">
					<div class="mentit"><?php _e( 'Categories', 'shiword' ); ?></div>
					<ul class="solid_ul">
						<?php shiword_get_categories_wpr(); ?>
						<li class="sw-link-to-allcat"><a title="<?php _e( 'View all categories', 'shiword' ); ?>" href="<?php echo esc_url( home_url() . '/?allcat=y' ); ?>"><?php _e( 'More...', 'shiword' ); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $shiword_opt['shiword_qbar_reccom'] == 1 ) { // recent comments menu ?>
		<div class="menuitem">
			<div  class="menuitem_img mii_rcomm"></div>
			<div class="menuback">
				<div class="menu_sx">
					<div class="mentit"><?php _e( 'Recent Comments', 'shiword' ); ?></div>
					<ul class="solid_ul">
						<?php shiword_get_recentcomments(); ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $shiword_opt['shiword_qbar_user'] == 1 ) { // user links menu ?>
		<div class="menuitem" id="user_menuback">
			<div  class="menuitem_img mii_cuser"></div>
			<div class="menuback">
				<div class="menu_sx">
					<div class="mentit"><?php _e( 'User', 'shiword' ); ?></div>
					<ul class="solid_ul">
						<li id="logged">
							<?php
							if ( is_user_logged_in() ) { //fix for notice when user not log-in
								get_currentuserinfo();
								$sw_email = $current_user->user_email;
								echo get_avatar( sanitize_email( $sw_email ), 50, $default= get_template_directory_uri() . '/images/user.png','user-avatar' );
								printf( __( 'Logged in as %s', 'shiword' ), '<strong>' . $current_user->display_name . '</strong>' );
							} else {
								echo get_avatar( 'dummyemail', 50, $default= get_template_directory_uri() . '/images/user.png','user-avatar' );
								echo __( 'Not logged in', 'shiword' );
							}
							?>
						</li>
						<?php if ( ! is_user_logged_in() || current_user_can( 'read' ) ) { wp_register(); }?>
						<?php if ( is_user_logged_in() ) { ?>
							<?php if ( current_user_can( 'read' ) ) { ?>
								<li><a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Your Profile', 'shiword' ); ?></a></li>
								<?php if ( current_user_can( 'publish_posts' ) ) { ?>
									<li><a title="<?php _e( 'Add New Post', 'shiword' ); ?>" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php _e( 'Add New Post', 'shiword' ); ?></a></li>
								<?php } ?>
								<?php if ( current_user_can( 'moderate_comments' ) ) {
									$sw_awaiting_mod = wp_count_comments();
									$sw_awaiting_mod = $sw_awaiting_mod->moderated;
									$sw_awaiting_mod = $sw_awaiting_mod ? ' (' . number_format_i18n( $sw_awaiting_mod ) . ')' : '';
								?>
									<li><a title="<?php _e( 'Comments', 'shiword' ); ?>" href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php _e( 'Comments', 'shiword' ); ?></a><?php echo $sw_awaiting_mod; ?></li>
								<?php } ?>
							<?php } ?>
							<li><a title="<?php _e( 'Log out', 'shiword' ); ?>" href="<?php echo esc_url( wp_logout_url() ); ?>"><?php _e( 'Log out', 'shiword' ); ?></a></li>
						<?php } ?>
						<?php if ( ! is_user_logged_in() ) {?>
							<?php shiword_mini_login(); ?>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<!-- end quickbar -->

<?php

	}
}


/**
 * the statusbar
 *
 */
if ( !function_exists( 'shiword_statusbar' ) ) {
	function shiword_statusbar(){
		global $shiword_opt, $current_user;
?>

<!-- begin statusbar -->
<div id="statusbar">
	<?php if ( $shiword_opt['shiword_welcome'] == 1 ) { ?>
		<?php printf( __( 'Welcome %s', 'shiword' ), ( is_user_logged_in() ) ? $current_user->display_name : '' ); ?>, <?php printf( __('today is %1$s, %2$s','shiword'), date_i18n( __( 'l','shiword' ) ), date_i18n( get_option( 'date_format' ) ) ); ?>
	<?php } ?>
	<?php shiword_hook_statusbar(); ?>
</div>
<!-- end statusbar -->

<?php	
	}
}


/**
 * the navigation bar
 *
 */
if ( !function_exists( 'shiword_navbuttons' ) ) {
	function shiword_navbuttons( $print = 1, $comment = 1, $feed = 1, $trackback = 1, $home = 1, $next_prev = 1, $up_down = 1 ) {
		global $post, $shiword_opt, $shiword_is_allcat_page;

		if ( $shiword_opt['shiword_navbuttons'] == 0 ) return;

		wp_reset_postdata();
		
		$is_post = is_single() && !is_attachment() && !$shiword_is_allcat_page;
		$is_image = is_attachment() && !$shiword_is_allcat_page;
		$is_page = is_singular() && !is_single() && !is_attachment() && !$shiword_is_allcat_page;
		$is_singular = is_singular() && !$shiword_is_allcat_page;

?>

<div id="navbuttons">

	<?php // ------- Print -------
		if ( $shiword_opt['shiword_navbuttons_print'] && $print && $is_singular ) { ?>
		<div class="minibutton">
			<a rel="nofollow" href="<?php
				$query_vars['style'] = 'printme';
				if ( get_query_var('page') ) {
					$query_vars['page'] = esc_html( get_query_var( 'page' ) );
				}
				if ( get_query_var( 'cpage' ) ) {
					$query_vars['cpage'] = esc_html( get_query_var( 'cpage' ) );
				}
				echo add_query_arg( $query_vars, get_permalink( $post->ID ) );
				?>">
				<span class="minib_img minib_print">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php _e( 'print preview','shiword' ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Leave a comment -------
		if ( $shiword_opt['shiword_navbuttons_comment'] && $comment && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { ?>
		<div class="minibutton">
			<a href="#respond">
				<span class="minib_img minib_comment">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php _e( 'Leave a comment','shiword' ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- RSS feed -------
		if ( $shiword_opt['shiword_navbuttons_feed'] && $feed && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { ?>
		<div class="minibutton">
			<a href="<?php echo get_post_comments_feed_link( $post->ID, 'rss2' ); ?> ">
				<span class="minib_img minib_rss">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php _e( 'feed for comments on this post', 'shiword' ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Trackback -------
		if ( $shiword_opt['shiword_navbuttons_trackback'] && $trackback && $is_singular && pings_open() ) { ?>
		<div class="minibutton">
			<a href="<?php echo get_trackback_url(); ?>" rel="trackback">
				<span class="minib_img minib_track">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php _e( 'Trackback URL','shiword' ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Home -------
		if ( $shiword_opt['shiword_navbuttons_home'] && $home ) { ?>
		<div class="minibutton">
			<a href="<?php echo home_url(); ?>">
				<span class="minib_img minib_home">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php _e( 'Home','shiword' ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Back to parent post -------
		if ( $is_image ) { ?>
		<?php if ( !empty( $post->post_parent ) ) { ?>
			<div class="minibutton">
				<a href="<?php echo get_permalink( $post->post_parent ); ?>" rel="gallery">
					<span class="minib_img minib_backtopost">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php esc_attr( printf( __( 'Return to %s', 'shiword' ), get_the_title( $post->post_parent ) ) ); ?></span>
			</div>
		<?php } ?>
	<?php } ?>

	<?php // ------- Next post -------
		if ( $shiword_opt['shiword_navbuttons_nextprev'] && $next_prev && $is_post && get_next_post() ) { ?>
		<div class="minibutton">
			<a href="<?php echo get_permalink( get_next_post() ); ?>">
				<span class="minib_img minib_npage">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php esc_attr( printf( __( 'Next Post', 'shiword' ) . ': %s', get_the_title( get_next_post() ) ) ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Previous post -------
		if ( $shiword_opt['shiword_navbuttons_nextprev'] && $next_prev && $is_post && get_previous_post() ) { ?>
		<div class="minibutton">
			<a href="<?php echo get_permalink( get_previous_post() ); ?>">
				<span class="minib_img minib_ppage">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php esc_attr( printf( __( 'Previous Post', 'shiword' ) . ': %s', get_the_title( get_previous_post() ) ) ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Newer Posts -------
		if ( $shiword_opt['shiword_navbuttons_newold'] && $next_prev && !$is_singular && !$shiword_is_allcat_page && get_previous_posts_link() ) { ?>
		<div class="minibutton">
			<?php previous_posts_link( '<span class="minib_img minib_ppages">&nbsp;</span>' ); ?>
			<span class="nb_tooltip"><?php echo __( 'Newer Posts', 'shiword' ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Older Posts -------
		if ( $shiword_opt['shiword_navbuttons_newold'] && $next_prev && !$is_singular && !$shiword_is_allcat_page && get_next_posts_link() ) { ?>
		<div class="minibutton">
			<?php next_posts_link( '<span class="minib_img minib_npages">&nbsp;</span>' ); ?>
			<span class="nb_tooltip"><?php echo __( 'Older Posts', 'shiword' ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Top -------
		if ( $shiword_opt['shiword_navbuttons_topbottom'] && $up_down ) { ?>
		<div class="minibutton">
			<a href="#">
				<span class="minib_img minib_top">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php _e( 'Top of page', 'shiword' ); ?></span>
		</div>
	<?php } ?>

	<?php // ------- Bottom -------
		if ( $shiword_opt['shiword_navbuttons_topbottom'] && $up_down ) { ?>
		<div class="minibutton">
			<a href="#footer">
				<span class="minib_img minib_bottom">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php _e( 'Bottom of page', 'shiword' ); ?></span>
		</div>
	<?php } ?>
	<div class="fixfloat"> </div>
</div>

<?php

	}
}

// Get Recent Comments
if ( !function_exists( 'shiword_get_recentcomments' ) ) {
	function shiword_get_recentcomments( $echo = 1 ) {
		$comments = get_comments( 'status=approve&number=10&type=comment' ); // valid type values (not documented) : 'pingback','trackback','comment'
		$output = '';
		if ( $comments ) {
			foreach ( $comments as $comment ) {
				//if ( post_password_required( get_post( $comment->comment_post_ID ) ) ) { continue; } // uncomment to skip comments on protected posts. Hi Emma ;)
				$post = get_post( $comment->comment_post_ID );
				setup_postdata( $post );
				//shrink the post title if > 35 chars
				$post_title_short = mb_strimwidth( get_the_title( $post->ID ), 0, 35, '&hellip;' );
				if ( post_password_required( $post ) ) {
					//hide comment author in protected posts
					$com_auth = __( 'someone','shiword' );
				} else {
					//shrink the comment author if > 20 chars
					$com_auth = mb_strimwidth( $comment->comment_author, 0, 20, '&hellip;' );
				}
				$output .= '<li>'. $com_auth . ' ' . __( 'on', 'shiword' ) . ' <a href="' . get_permalink( $post->ID ) . '#comment-' . $comment->comment_ID . '">' . $post_title_short . '</a><div class="preview">';
			if ( post_password_required( $post ) ) {
				$output .= '[' . __( 'No preview: this is a comment of a protected post', 'shiword' ) . ']';
			} else {
				$output .= get_comment_excerpt( $comment->comment_ID );
			}
				$output .= '</div></li>';
			}
		} else {
			$output .= '<li>' . __( 'No comments yet.', 'shiword' ) . '</li>';
		}
		if ( $echo )
			echo $output;
		else
			return $output;
	}
}

// Get Recent Entries
if ( !function_exists( 'shiword_get_recententries' ) ) {
	function shiword_get_recententries( $number = 10 ) {
		$r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) {
			while ($r->have_posts()) {
				$r->the_post();

				$post_title = get_the_title();
				//shrink the post title if > 35 chars
				$post_title_short = mb_strimwidth( $post_title, 0, 35, '&hellip;' );
				
				$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );

				echo '<li><a href="' . get_permalink() . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . $post_title_short . '</a> ' . sprintf( __( 'by %s', 'shiword' ), $post_auth ) . '<div class="preview">';
				if ( post_password_required() ) {
					echo '<img class="alignleft wp-post-image"  height="50" width="50" src="' . get_template_directory_uri() . '/images/thumb.png" alt="thumb" title="' . $post_title_short . '" />';
					echo '[' . __('No preview: this is a protected post', 'shiword' ) . ']';
				} else {
					echo shiword_get_the_thumb( get_the_ID(), 50, 50, 'alignleft' );
					the_excerpt();
				}
				echo '</div></li>';
			}
		}
		wp_reset_postdata();
	}
}

// Get Categories List (with posts related)
if ( !function_exists( 'shiword_get_categories_wpr' ) ) {
	function shiword_get_categories_wpr() {
		$args=array(
			'orderby' => 'count',
			'number' => 10,
			'order' => 'DESC'
		);
		$categories = get_categories( $args );
		foreach( $categories as $category ) {
			$cat_title = category_description( $category->cat_ID ) ? esc_attr( strip_tags( category_description( $category->cat_ID ) ) ) : sprintf( __( 'View all posts in %s', 'shiword' ), $category->name );
			echo '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . $cat_title . '" >' . $category->name . '</a> (' . $category->count . ')<div class="cat_preview"><div class="mentit">' . __( 'Recent Posts', 'shiword' ) . '</div><ul class="solid_ul">';
			$tmp_cat_ID = $category->cat_ID;
			$post_search_args = array(
				'numberposts' => 5,
				'category' => $tmp_cat_ID,
				'no_found_rows' => true
				);
			$lastcatposts = get_posts( $post_search_args ); //get the post list for each category
			foreach( $lastcatposts as $post ) {
				setup_postdata( $post );
				$post_title = get_the_title( $post->ID );
				//shrink the post title if > 35 chars
				$post_title_short = mb_strimwidth( $post_title, 0, 35, '&hellip;' );
				//shrink the post author if > 20 chars
				$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );
				echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . esc_html( $post_title ) . '">' . $post_title_short . '</a> ' . sprintf( __( 'by %s', 'shiword' ), $post_auth ) . '</li>';
			}
			echo '</ul></div></li>';
		}
	}
}

?>
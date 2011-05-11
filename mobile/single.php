<?php
	global $shiword_opt;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name = "viewport" content = "width = device-width">
		<title>
			<?php
			if ( is_front_page() ) {
				bloginfo( 'name' ); ?> - <?php bloginfo( 'description' );
			} else {
				wp_title( '&laquo;', true, 'right' );
				bloginfo( 'name' );
			}
			?>
		</title>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_get_archives( 'type=monthly&format=link' ); ?>
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> 
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<div id="main">
			<div id="head">
				<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
			</div>
			<?php if ( have_posts() ) { ?>
				<?php while ( have_posts() ) { 
					the_post(); ?>
					<div class="sw-navi halfsep">
							<span class="sw-halfspan sw-prev"><?php next_post_link('%link'); ?></span>
							<span class="sw-halfspan sw-next"><?php previous_post_link('%link'); ?></span>
							<div class="fixfloat"> </div>
					</div>
					<div <?php post_class( 'sw-post' ) ?> id="post-<?php the_ID(); ?>">
						<h2><?php 
							$post_title = the_title( '','',false );
							if ( !$post_title ) {
								_e( '(no title)', 'shiword' );
							} else {
								echo $post_title;
							}
							?>
						</h2>
						<?php the_content(); ?>
						<?php if ( ! is_page() ) { ?>
							<div class="commentmetadata fixfloat">
								<?php if ( ( $shiword_opt['shiword_byauth'] == 1 ) ) { printf( __( 'by %s', 'shiword' ), '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( 'View all posts by %s', esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' ); echo '<br />'; }; ?>
								<?php if ( ( $shiword_opt['shiword_xinfos_date'] == 1 ) ) { printf( __( 'Published on: %1$s', 'shiword' ), get_the_time( get_option( 'date_format' ) ) ) ; echo '<br />'; }?>
								<?php if ( ( $shiword_opt['shiword_xinfos_cat'] == 1 ) ) { echo __( 'Categories', 'shiword' ) . ':'; the_category( ', ' ); echo '<br />'; } ?>
								<?php if ( ( $shiword_opt['shiword_xinfos_tag'] == 1 ) ) { echo __( 'Tags:', 'shiword' ) . ' '; if ( !get_the_tags() ) { _e( 'No Tags', 'shiword' ); } else { the_tags('', ', ', ''); }; echo '<br />';  } ?>
								<?php edit_post_link( __( 'Edit', 'shiword' ) ); ?>
							</div>
						<?php } ?>
						<div class="sw-pc-navi">
							<?php wp_link_pages(); ?>
						</div>
					</div>
					<?php comments_template('/mobile/comments.php'); ?>
					<div class="sw-navi halfsep">
							<span class="sw-halfspan sw-prev"><?php next_post_link('%link'); ?></span>
							<span class="sw-halfspan sw-next"><?php previous_post_link('%link'); ?></span>
							<div class="fixfloat"> </div>
					</div>
					<?php if (is_page()) {
						$args = array(
							'post_type' => 'page',
							'post_parent' => $post->ID,
							'order' => 'ASC',
							'orderby' => 'menu_order',
							'numberposts' => 0
							);
						$sub_pages = get_posts( $args ); // retrieve the child pages
					} else {
						$sub_pages = '';
					}

					if (!empty($sub_pages)) { ?>
						<h2 class="sw-seztit"><span><?php _e( 'Child pages: ', 'shiword' ); ?></span></h2>
						<ul class="sw-group">
							<?php 
							foreach ( $sub_pages as $children ) {
								echo '<li><a href="' . get_permalink( $children ) . '" title="' . esc_attr( strip_tags( get_the_title( $children ) ) ) . '">' . get_the_title( $children ) . '</a></li>';
							}
							?>
						</ul>
						
					<?php } ?>
					<?php $the_parent_page = $post->post_parent; // retrieve the parent page
					if ( $the_parent_page ) {?>
						<h2 class="sw-seztit"><span><?php _e( 'Parent page: ', 'shiword' ); ?></span></h2>
						<ul class="sw-group">
								<li><a href="<?php echo get_permalink( $the_parent_page ); ?>" title="<?php echo esc_attr( strip_tags( get_the_title( $the_parent_page ) ) ); ?>"><?php echo get_the_title( $the_parent_page ); ?></a></li>
						</ul>
					<?php } ?>
				<?php } ?>
			<?php } else { ?>
				<p class="sw-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>
			<?php } ?>
			<h2 class="sw-seztit"><span><?php _e( 'Search', 'shiword' ); ?></span></h2>
			<div class="sw-navi">
				<form id="search" action="<?php echo home_url(); ?>" method="get">
					<div>
						<input type="text" name="s" id="s" inputmode="predictOn" value="" />
						<input type="submit" name="submit_button" value="Search" />
					</div>
				</form>
			</div>
			<h2 class="sw-seztit"><span><?php _e( 'Pages', 'shiword' ); ?></span></h2>
			<?php wp_nav_menu( array( 'menu_class' => 'sw-group', 'menu_id' => 'mainmenu', 'fallback_cb' => 'shiword_pages_menu_mobile', 'theme_location' => 'primary', 'depth' => 1 ) ); //main menu ?>
			<?php if ( $shiword_opt['shiword_qbar_reccom'] == 1 ) { // recent comments menu ?>
				<h2 class="sw-seztit"><span><?php _e( 'Recent Comments', 'shiword' ); ?></span></h2>
				<ul id="sw-reccom">
					<?php shiword_get_recentcomments(); ?>
				</ul>
			<?php } ?>
			<h2 class="sw-seztit"><span>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?></span></h2>
			<p id="themecredits">
				<?php if ( $shiword_opt['shiword_tbcred'] == 1 ) { ?>
					Powered by <a href="http://wordpress.org"><strong>WordPress</strong></a> and <a href="http://www.twobeers.net/"><strong>Shiword</strong></a>. 
				<?php } ?>
				<?php wp_loginout(); wp_register(' | ', ''); ?>
			</p>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
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
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<div id="main">
			<div id="head">
				<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
			</div>
			<?php // search reminder
			if ( is_archive() ) { ?>
				<div class="sw-padded">
					<?php 
						if ( is_category() )	{ $sw_strtype = __( 'Category', 'shiword' ) . ' : %s'; }
						elseif ( is_tag() )		{ $sw_strtype = __( 'Tag', 'shiword' ) . ' : %s'; }
						elseif ( is_date() )	{ $sw_strtype = __( 'Archives', 'shiword' ) . ' : %s'; }
						elseif (is_author()) 	{ $sw_strtype = __( 'Posts by %s', 'shiword') ; }
					?>
					<?php printf( $sw_strtype, '<strong>' . wp_title( '',false ) . '</strong>'); ?>
				</div>
			<?php } elseif ( is_search() ) { ?>
				<div class="sw-padded">
					<?php printf( __( 'Search results for &#8220;%s&#8221;', 'shiword' ), '<strong>' . esc_html( get_search_query() ) . '</strong>' ); ?>
				</div>
			<?php } ?>
			<?php if ( have_posts() ) { ?>
				<h2 class="sw-seztit"><span><?php _e( 'Posts', 'shiword' ); ?></span></h2>
				<ul class="sw-group">
				<?php while ( have_posts() ) {
					the_post(); ?>
					<?php $sw_alter_style = ( !isset($sw_alter_style) || $sw_alter_style == 'sw-odd' ) ? 'sw-even' : 'sw-odd'; ?>
					<li class="<?php echo $sw_alter_style; ?>">
						<a href="<?php the_permalink() ?>" rel="bookmark"><?php 
							$sw_post_title = the_title( '','',false );
							if ( !$sw_post_title ) {
								_e( '(no title)', 'shiword' );
							} else {
								echo $sw_post_title;
							}
							?><br /><span class="sw-details"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php comments_number('(0)', '(1)','(%)'); ?></span>
						</a>
					</li>
				<?php } ?>
				</ul>
				<?php //num of pages
				global $paged;
				if ( !$paged ) { $paged = 1; }
				?>
				<h2 class="sw-seztit"><span><?php printf( __( 'page %1$s of %2$s', 'shiword' ), $paged, $wp_query->max_num_pages ); ?></span></h2>
				<div class="sw-navi halfsep">
						<span class="sw-halfspan sw-prev"><?php previous_posts_link( __( 'Previous page', 'shiword' ) ); ?></span>
						<span class="sw-halfspan sw-next"><?php next_posts_link( __( 'Next page', 'shiword' ) ); ?></span>
						<div class="fixfloat"> </div>
				</div>
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
				<?php wp_loginout(); wp_register(' | ', ''); ?><?php if ( ( !isset( $shiword_opt['shiword_mobile_css'] ) || ( $shiword_opt['shiword_mobile_css'] == 1) ) ) echo ' | <a href="' . home_url() . '?mobile_override=desktop">'. __('Switch to Desktop View','shiword') .'</a>'; ?>
			</p>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

		<title><?php
			if ( is_front_page() ) {
				bloginfo( 'name' ); ?> - <?php bloginfo( 'description' );
			} else {
				wp_title( '&laquo;', true, 'right' );
				bloginfo( 'name' );
			}
			?></title>

	<?php
		global $shiword_opt, $shiword_is_printpreview;
	?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> 
	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>
	<div id="sw_background">
		<div id="sw_body" class="pad_bg">
			<div id="sw_body_overlay">
				<div id="sw_body_inner"></div>
			</div>
		</div>
	</div>
	<div id="main">
		<div id="head_cont">
			<div id="head" class="pad_bg">
				<div id="head_overlay">
					<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
					<div class="description"><?php bloginfo( 'description' ); ?></div>
					<div id="rss_imglink" class="minibutton">
						<a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php _e( 'Syndicate this site using RSS 2.0', 'shiword' ); ?>">
							<span class="minib_img" style="background-position: 0px -216px;">&nbsp;</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	<div id="maincontent">
		<div id="headerimg">
			<?php get_sidebar( 'header' ); // show header widgets areas ?>
		</div>
		<?php $headmenu = wp_nav_menu( array( 'echo' => 0, 'menu_id' => 'mainmenu', 'fallback_cb' => 'shiword_pages_menu', 'theme_location' => 'primary' ) ); //main menu ?>
		<?php if ( $headmenu ) echo '<div class="sw-menu">'.$headmenu.'<div style="height:4px;" class="fixfloat"> </div></div>' ?>
		<?php  // the sticky slider 
			if ( $shiword_opt['shiword_sticky'] == 1 && !is_404() && !$shiword_is_printpreview ) {
				if (
					( is_page() && ( $shiword_opt['shiword_sticky_pages'] == 1 ) ) ||
					( is_single() && ( $shiword_opt['shiword_sticky_posts'] == 1 ) ) ||
					( is_front_page() && ( $shiword_opt['shiword_sticky_front'] == 1 ) ) ||
					( ( is_archive() || is_search() ) && ( $shiword_opt['shiword_sticky_over'] == 1 ) )
				) shiword_sticky_slider(); 
			}
		?>

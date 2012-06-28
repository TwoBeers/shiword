<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> itemscope itemtype="http://schema.org/Blog">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name = "viewport" content = "width = device-width" />

<?php if ( is_singular() ) { ?> 
	<meta itemprop="name" content="<?php the_title(); ?>">
	<?php if( has_post_thumbnail() ) { $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id() ); ?><meta itemprop="image" content="<?php echo $image_attributes[0]; ?>"><?php } ?>
<?php } ?> 

	<title><?php
		if ( is_front_page() ) {
			bloginfo( 'name' ); ?> - <?php bloginfo( 'description' );
		} else {
			wp_title( '&laquo;', true, 'right' );
			bloginfo( 'name' );
		}
		?></title>

	<?php global $shiword_opt; ?>

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

	<?php wp_head(); ?>

</head>

<body <?php body_class( array( 'sw-no-js', 'body-' . $shiword_opt['shiword_frame_width'] ) ); ?>>
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
					<?php if ( $shiword_opt['shiword_site_title'] ) { ?><h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1><?php } ?>
					<?php if ( $shiword_opt['shiword_site_description'] ) { ?><div class="description"><?php bloginfo( 'description' ); ?></div><?php } ?>
					<div id="rss_imglink" class="minibutton">
						<a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php _e( 'Syndicate this site using RSS 2.0', 'shiword' ); ?>">
							<span class="minib_img">&nbsp;</span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div id="maincontent">
			<?php shiword_hook_before_header(); ?>
			<div id="headerimg">
				<?php shiword_get_sidebar( 'header' ); // show header widget area ?>
			</div>
			<?php shiword_hook_after_header(); ?>
			
			
<?php if ( ! $shiword_opt['shiword_hide_primary_menu'] )
	wp_nav_menu( array( 'container_class' => 'sw-menu', 'menu_id' => 'mainmenu', 'fallback_cb' => 'shiword_pages_menu', 'theme_location' => 'primary' ) ); //main menu ?>
			
			
			
			
			
			<?php  // the sticky slider 
				if ( $shiword_opt['shiword_sticky'] == 1 && !is_404() ) {
					if (
						( is_page() && ( $shiword_opt['shiword_sticky_pages'] == 1 ) ) ||
						( is_single() && ( $shiword_opt['shiword_sticky_posts'] == 1 ) ) ||
						( is_front_page() && ( $shiword_opt['shiword_sticky_front'] == 1 ) ) ||
						( ( is_archive() || is_search() ) && ( $shiword_opt['shiword_sticky_over'] == 1 ) )
					) shiword_slider(); 
				}
			?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

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

	<?php
		global $shiword_opt;
		global $shiword_preview_mode;
	?>

	<style type="text/css">
		#headerimg {
			background: transparent url('<?php esc_url ( header_image() ); ?>') right bottom repeat-y;
		}
	</style>
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> 
	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>
	<div id="sw_background"></div>
	<div id="main">
		<div id="head_cont">
			<div id="head">
				<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
				<div class="description"><?php bloginfo('description'); ?></div>
			</div>
		</div>
	<div id="maincontent">
		<div id="headerimg">
			<?php get_sidebar( 'header' ); // show header widgets areas ?>
		</div>
		<div id="pages">
			<?php wp_nav_menu( array( 'menu_id' => 'mainmenu', 'fallback_cb' => 'shiword_pages_menu', 'theme_location' => 'primary' ) ); ?>
			<div id="rss_imglink"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><img alt="rsslink" src="<?php bloginfo('stylesheet_directory'); ?>/images/rss.png" /></a></div>
			<div class="fixfloat"></div>
		</div>
		<?php if ( ($shiword_opt['shiword_sticky'] == 'true') && is_home() ) sticky_slide(); // the sticky slider?> 
		<div class="<?php if ( is_singular() || ($shiword_opt['shiword_rsideb'] == 'false') ) { echo 'posts_wide'; } else { echo 'posts_narrow'; } ?> <?php if ( $shiword_opt['shiword_sticky'] != 'true' ) echo 'letsstick'; ?>">
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
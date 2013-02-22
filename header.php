<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> itemscope itemtype="http://schema.org/Blog">

<head profile="http://gmpg.org/xfn/11">

	<?php shiword_hook_head_top(); ?>

	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

	<meta name = "viewport" content = "width = device-width" />

	<title><?php wp_title( '&laquo;', true, 'right' ); ?></title>

	<?php global $shiword_opt; ?>

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>

	<?php shiword_hook_head_bottom(); ?>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<?php shiword_hook_body_top(); ?>

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
					<?php shiword_site_title(); ?>
				</div>
			</div>
		</div>

		<div id="maincontent">

			<?php shiword_hook_header_before(); ?>

			<div id="headerimg">

				<?php shiword_hook_header_top(); ?>

				<?php shiword_get_sidebar( 'header' ); // show header widget area ?>

				<?php shiword_hook_header_bottom(); ?>

			</div>

			<?php shiword_hook_header_after(); ?>

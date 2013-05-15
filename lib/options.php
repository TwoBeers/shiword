<?php 
/**
 * options.php
 *
 * The options array
 *
 * @package Shiword
 * @since 3.0
 */


//complete options array, with type, defaults values, description, infos and required option
function shiword_get_coa( $option = false ) {

	$shiword_groups = array(
							'other'			=> __( 'other' , 'shiword' ),
							'mobile'		=> __( 'mobile' , 'shiword' ),
							'slideshow'		=> __( 'slideshow' , 'shiword' ),
							'sidebar'		=> __( 'sidebar' , 'shiword' ),
							'postformats'	=> __( 'post formats' , 'shiword' ),
							'fonts'			=> __( 'fonts' , 'shiword' ),
							'content'		=> __( 'content' , 'shiword' ),
							'navigation'	=> __( 'navigation' , 'shiword' )
	);

	$shiword_groups = apply_filters( 'shiword_options_groups', $shiword_groups );

	$shiword_coa = array(
		'shiword_qbar' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'sliding menu', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'shiword_qbar_recpost', 'shiword_qbar_cat', 'shiword_qbar_reccom', 'shiword_qbar_user', '', 'shiword_qbar_minilogin' )
		),
		'shiword_qbar_user' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'user', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_qbar',
							'sub'				=> false
		),
		'shiword_qbar_minilogin' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( '-- mini login', 'shiword' ),
							'info'				=> __( 'a small login form in the user panel', 'shiword' ),
							'req'				=> 'shiword_qbar_user',
							'sub'				=> false
		),
		'shiword_qbar_reccom' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'recent comments', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_qbar',
							'sub'				=> false
		),
		'shiword_qbar_cat' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'categories', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_qbar',
							'sub'				=> false
		),
		'shiword_qbar_recpost' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'recent posts', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_qbar',
							'sub'				=> false
		),
		'shiword_navbuttons' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'navigation buttons', 'shiword' ),
							'info'				=> __( "the fixed navigation bar on the right. Note: Is strongly recommended to keep it enabled", 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_navbuttons_print', 'shiword_navbuttons_comment', 'shiword_navbuttons_feed', 'shiword_navbuttons_trackback', 'shiword_navbuttons_home', 'shiword_navbuttons_nextprev', 'shiword_navbuttons_newold', 'shiword_navbuttons_topbottom' )
		),
		'shiword_navbuttons_print' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'print preview', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_navbuttons',
							'sub'				=> false
		),
		'shiword_navbuttons_comment' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'leave a comment', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_navbuttons',
							'sub'				=> false
		),
		'shiword_navbuttons_feed' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'RSS feed', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_navbuttons',
							'sub'				=> false
		),
		'shiword_navbuttons_trackback' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'trackback', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_navbuttons',
							'sub'				=> false
		),
		'shiword_navbuttons_home' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'home', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_navbuttons',
							'sub'				=> false
		),
		'shiword_navbuttons_nextprev' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'next/previous post', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_navbuttons',
							'sub'				=> false
		),
		'shiword_navbuttons_newold' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'newer/older posts', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_navbuttons',
							'sub'				=> false
		),
		'shiword_navbuttons_topbottom' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'top/bottom', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_navbuttons',
							'sub'				=> false
		),
		'shiword_supadupa_title' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'enhanced post title', 'shiword' ),
							'info'				=> __( 'show the post title using the featured image as background (the image must be at least 700x200px)', 'shiword' ),
							'req'				=> ''
		),
		'shiword_manage_blank_titles' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'blank titles', 'shiword' ),
							'info'				=> __( 'set a standard text for blank titles', 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_blank_title' )
		),
		'shiword_blank_title' => array(
							'group'				=> 'content',
							'type'				=> 'txt',
							'default'			=> __( '(no title)', 'shiword' ),
							'description'		=> __( 'format', 'shiword' ),
							'info'				=> __( 'you may use these codes: <code>%d</code> date, <code>%f</code> format (if any), <code>%n</code> ID', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_xcont' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'content summary', 'shiword' ),
							'info'				=> __( 'use the summary instead of content in main index', 'shiword' ) . ' (<a href="http://codex.wordpress.org/Template_Tags/the_excerpt" target="_blank">Codex</a>)',
							'req'				=> '',
							'sub'				=> array( 'shiword_xcont_lenght', 'shiword_xcont_more_txt', 'shiword_xcont_more_link' )
		),
		'shiword_xcont_lenght' => array(
							'group'				=> 'content',
							'type'				=> 'int',
							'default'			=> 55,
							'description'		=> __( 'excerpt lenght', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_xcont_more_txt' => array(
							'group'				=> 'content',
							'type'				=> 'txt',
							'default'			=> '[...]',
							'description'		=> __( '<em>excerpt more</em> string', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_xcont_more_link' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( '<em>excerpt more</em> linked', 'shiword' ),
							'info'				=> __( 'use the <em>excerpt more</em> string as a link to the full post', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_pthumb' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'posts thumbnail', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_xcont',
							'sub'				=> array( 'shiword_pthumb_size' )
		),
		'shiword_pthumb_size' => array(
							'group'				=> 'content',
							'type'				=> 'sel',
							'default'			=> 120,
							'options'			=> array( 64, 96, 120 ),
							'options_readable'	=> array( '64px', '96px', '120px' ),
							'description'		=> __( 'thumbnail size', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_xinfos_global' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'posts extra info', 'shiword' ),
							'info'				=> __( 'show extra info (author, date, tags, etc)', 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_byauth', 'shiword_xinfos_date', 'shiword_xinfos_comm', 'shiword_xinfos_tag', 'shiword_xinfos_cat', 'shiword_xinfos', '', 'shiword_xinfos_static' )
		),
		'shiword_xinfos' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in posts overview', 'shiword' ),
							'info'				=> __( 'show extra info in posts overview', 'shiword' ),
							'req'				=> 'shiword_xinfos_global',
							'sub'				=> false
		),
		'shiword_xinfos_static' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'static info', 'shiword' ),
							'info'				=> __( 'show extra info as a static list (not dropdown animated)', 'shiword' ),
							'req'				=> 'shiword_xinfos_global',
							'sub'				=> false
		),
		'shiword_byauth' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post author', 'shiword' ),
							'info'				=> __( 'show author on posts info', 'shiword' ),
							'req'				=> 'shiword_xinfos_global',
							'sub'				=> false
		),
		'shiword_xinfos_date' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post date', 'shiword' ),
							'info'				=> __( 'show date on posts info', 'shiword' ),
							'req'				=> 'shiword_xinfos_global',
							'sub'				=> false
		),
		'shiword_xinfos_comm' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post comments', 'shiword' ),
							'info'				=> __( 'show comments on posts/pages info', 'shiword' ),
							'req'				=> 'shiword_xinfos_global',
							'sub'				=> false
		),
		'shiword_xinfos_tag' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post tags', 'shiword' ),
							'info'				=> __( 'show tags on posts info', 'shiword' ),
							'req'				=> 'shiword_xinfos_global',
							'sub'				=> false
		),
		'shiword_xinfos_cat' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post categories', 'shiword' ),
							'info'				=> __( 'show categories on posts info', 'shiword' ),
							'req'				=> 'shiword_xinfos_global',
							'sub'				=> false
		),
		'shiword_cat_description' => array(
							'group'				=> 'content',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'category description', 'shiword' ),
							'info'				=> __( 'show the category description in search-per-category', 'shiword' ),
							'req'				=> ''
		),
		'shiword_more_tag' => array(
							'group'				=> 'content',
							'type'				=> 'txt',
							'default'			=> __( '(more...)', 'shiword' ),
							'description'		=> __( '"more" tag string', 'shiword' ),
							'info'				=> __( 'only plain text. use <code>%t</code> as placeholder for the post title', 'shiword' ) . ' (<a href="http://codex.wordpress.org/Customizing_the_Read_More" target="_blank">Codex</a>)',
							'req'				=> ''
		),
		'shiword_postformats' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post formats support', 'shiword' ),
							'info'				=> __( 'use the <a href="http://codex.wordpress.org/Post_Formats" target="_blank">Post Formats</a> new feature', 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_postformat_aside', 'shiword_postformat_audio', 'shiword_postformat_chat', 'shiword_postformat_gallery', 'shiword_postformat_image', 'shiword_postformat_link', 'shiword_postformat_quote', 'shiword_postformat_status', 'shiword_postformat_video' )
		),
		'shiword_postformat_aside' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'aside', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_postformat_audio' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'audio', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_postformat_chat' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'chat', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_postformat_gallery' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'gallery', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_postformat_image' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'image', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_postformat_link' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'link', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_postformat_quote' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'quote', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_postformat_status' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'status', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_postformat_video' => array(
							'group'				=> 'postformats',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'video', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_postformats',
							'sub'				=> false
		),
		'shiword_quotethis' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'quote link', 'shiword' ),
							'info'				=> __( 'show a link for easily add the selected text as a quote inside the comment form', 'shiword' ),
							'req'				=> ''
		),
		'shiword_rsideb' => array(
							'group'				=> 'sidebar',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'right sidebar', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'shiword_rsidebpages', 'shiword_rsidebposts', 'shiword_rsidebattachment' )
		),
		'shiword_rsidebpages' => array(
							'group'				=> 'sidebar',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'on pages', 'shiword' ),
							'info'				=> sprintf( __( 'show right sidebar on pages. If you prefer to hide the sidebar just for a single page, you can use the <a href="http://codex.wordpress.org/Pages#Page_Templates" target="_blank">page template</a> named "%s"', 'shiword' ), 'One column, no sidebar' ),
							'req'				=> 'shiword_rsideb',
							'sub'				=> false
		),
		'shiword_rsidebposts' => array(
							'group'				=> 'sidebar',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'on posts', 'shiword' ),
							'info'				=> __( 'show right sidebar on posts', 'shiword' ),
							'req'				=> 'shiword_rsideb',
							'sub'				=> false
		),
		'shiword_rsidebattachment' => array(
							'group'				=> 'sidebar',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'on attachments', 'shiword' ),
							'info'				=> __( 'show right sidebar on attachments', 'shiword' ),
							'req'				=> 'shiword_rsideb',
							'sub'				=> false
		),
		'shiword_jsani' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'javascript animations', 'shiword' ),
							'info'				=> __( 'try disable animations if you encountered problems with javascript', 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_basic_animation_main_menu', 'shiword_basic_animation_navigation_buttons', 'shiword_basic_animation_quickbar_panels', 'shiword_basic_animation_entry_meta', 'shiword_basic_animation_smooth_scroll', 'shiword_basic_animation_resize_video' )
		),
		'shiword_basic_animation_main_menu' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'main menu', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_jsani',
							'sub'				=> false
		),
		'shiword_basic_animation_navigation_buttons' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'navigation buttons', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_jsani',
							'sub'				=> false
		),
		'shiword_basic_animation_quickbar_panels' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'quickbar panels', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_jsani',
							'sub'				=> false
		),
		'shiword_basic_animation_entry_meta' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post details', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_jsani',
							'sub'				=> false
		),
		'shiword_basic_animation_smooth_scroll' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'smooth scroll', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_jsani',
							'sub'				=> false
		),
		'shiword_basic_animation_resize_video' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'video resize', 'shiword' ),
							'info'				=> '',
							'req'				=> 'shiword_jsani',
							'sub'				=> false
		),
		'shiword_thickbox' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'thickbox', 'shiword' ),
							'info'				=> __( 'use thickbox for showing images and galleries', 'shiword' ),
							'req'				=> 'shiword_jsani',
							'sub'				=> array( 'shiword_thickbox_bg', 'shiword_thickbox_link_to_image' )
		),
		'shiword_thickbox_bg' => array(
							'group'				=> 'other',
							'type'				=> 'col',
							'default'			=> '#000000',
							'description'		=> __( 'background color', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_thickbox_link_to_image' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'gallery links', 'shiword' ),
							'info'				=> __( 'force galleries to use links to image instead of links to attachment', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_tinynav' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> '<a href="https://github.com/viljamis/TinyNav.js">Tinynav</a>',
							'info'				=> __( 'tiny navigation menu for small screen', 'shiword' ),
							'req'				=> 'shiword_jsani'
		),
		'shiword_sticky' => array(
							'group'				=> 'slideshow',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'Slideshow', 'shiword' ),
							'info'				=> sprintf( __( 'a slideshow for your most important posts/pages. Select them in <a href="%s">posts</a> list or in <a href="%s">pages</a> list by clicking "Add to Slideshow" link for each post/page you wanto to add. A small icon %s will appear beside the title. Click "Remove from Slideshow" to remove', 'shiword' ), admin_url( 'edit.php' ), admin_url( 'edit.php?post_type=page' ), '<img src="' . get_template_directory_uri().'/images/inslider.png" alt="in slider" />' ),
							'req'				=> 'shiword_jsani',
							'sub'				=> array( 'shiword_sticky_front', 'shiword_sticky_pages', 'shiword_sticky_posts', 'shiword_sticky_over', '', 'shiword_sticky_height', '', 'shiword_sticky_author', '', 'shiword_sticky_speed', 'shiword_sticky_pause' )
		),
		'shiword_sticky_front' => array(
							'group'				=> 'slideshow',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in home/front page', 'shiword' ),
							'info'				=> __( 'display slideshow in home/front page', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_sticky_pages' => array(
							'group'				=> 'slideshow',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'in pages', 'shiword' ),
							'info'				=> __( 'display slideshow in pages', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_sticky_posts' => array(
							'group'				=> 'slideshow',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'in posts', 'shiword' ),
							'info'				=> __( 'display slideshow in posts', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_sticky_over' => array(
							'group'				=> 'slideshow',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in posts overview', 'shiword' ),
							'info'				=> __( 'display slideshow in posts overview (posts page, search results, archives, categories, etc.)', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_sticky_height' => array(
							'group'				=> 'slideshow',
							'type'				=> 'sel',
							'default'			=> '160px',
							'options'			=> array( '160px', '200px', '240px', '280px', '320px', '360px', '400px' ),
							'options_readable'	=> array( '160px', '200px', '240px', '280px', '320px', '360px', '400px' ),
							'description'		=> __( 'height', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_sticky_author' => array(
							'group'				=> 'slideshow',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post author', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_sticky_speed' => array(
							'group'				=> 'slideshow',
							'type'				=> 'int',
							'default'			=> 2500,
							'description'		=> __( 'animation speed', 'shiword' ) . ' (ms)',
							'info'				=> __( 'default <code>2500</code>', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_sticky_pause' => array(
							'group'				=> 'slideshow',
							'type'				=> 'int',
							'default'			=> 2000,
							'description'		=> __( 'pause duration', 'shiword' ) . ' (ms)',
							'info'				=> __( 'default <code>2000</code>', 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_navlinks' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'classic navigation links', 'shiword' ),
							'info'				=> __( "show the classic navigation links (paged posts navigation, next/prev post, etc). Note: the same links are already located in the easy-navigation bar", 'shiword' ),
							'req'				=> ''
		),
		'shiword_hide_primary_menu' => array(
							'group'				=> 'navigation',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'hide primary menu', 'shiword' ),
							'info'				=> '',
							'req'				=> ''
		),
		'shiword_site_title' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'site title', 'shiword' ),
							'info'				=> __( 'display the site title on the top left of the main frame', 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_site_description' )
		),
		'shiword_site_description' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'description', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_welcome' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'welcome message', 'shiword' ),
							'info'				=> __( "show the \"welcome\" message and the date reminder in the bottom right", 'shiword' ),
							'req'				=> ''
		),
		'shiword_head_h' => array(
							'group'				=> 'other',
							'type'				=> 'sel',
							'default'			=> '100px',
							'options'			=> array( '0px', '100px', '150px', '200px', '250px', '300px' ),
							'options_readable'	=> array( '0px', '100px', '150px', '200px', '250px', '300px' ),
							'description'		=> __( 'header height', 'shiword' ),
							'info'				=> '',
							'req'				=> ''
		),
		'shiword_frame_width' => array(
							'group'				=> 'other',
							'type'				=> 'sel',
							'default'			=> 850,
							'options'			=> array( 626, 850, 1106 ),
							'options_readable'	=> array( __( 'narrow', 'shiword' ), __( 'normal', 'shiword' ), __( 'wide', 'shiword' ) ),
							'description'		=> __( 'frame width', 'shiword' ),
							'info'				=> __( "Default is <u>normal</u>. If you modify the width of the main frame, your site may be displayed incorrectly for some of your readers. <u>Don't change this setting unless you're sure of what you're doing!</u>", 'shiword' ),
							'req'				=> ''
		),
		'shiword_font_family'=> array(
							'group'				=> 'fonts',
							'type'				=> 'sel',
							'default'			=> 'Verdana, Geneva, sans-serif',
							'description'		=> __( 'font family', 'shiword' ),
							'info'				=> '',
							'options'			=> array( 'Arial, sans-serif', 'Comic Sans MS, cursive', 'Courier New, monospace', 'Georgia, serif', 'Helvetica, sans-serif', 'Lucida Console, Monaco, monospace', 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'monospace', 'Palatino Linotype, Book Antiqua, Palatino, serif', 'Tahoma, Geneva, sans-serif', 'Times New Roman, Times, serif', 'Trebuchet MS, sans-serif', 'Verdana, Geneva, sans-serif' ),
							'options_readable'	=> array( 'Arial, sans-serif', 'Comic Sans MS, cursive', 'Courier New, monospace', 'Georgia, serif', 'Helvetica, sans-serif', 'Lucida Console, Monaco, monospace', 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'monospace', 'Palatino Linotype, Book Antiqua, Palatino, serif', 'Tahoma, Geneva, sans-serif', 'Times New Roman, Times, serif', 'Trebuchet MS, sans-serif', 'Verdana, Geneva, sans-serif' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_font_size' )
		),
		'shiword_font_size' => array(
							'group'				=> 'fonts',
							'type'				=> 'sel',
							'default'			=> '11px',
							'options'			=> array( '9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px' ),
							'options_readable'	=> array( '9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px' ),
							'description'		=> __( 'font size', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_google_font_family'=> array(
							'group'				=> 'fonts',
							'type'				=> 'txt',
							'default'			=> '',
							'description'		=> __( 'Google web font', 'shiword' ),
							'info'				=> __( 'Copy and paste <a href="http://www.google.com/webfonts" target="_blank"><strong>Google web font</strong></a> name here. Example: <code>Architects Daughter</code>', 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_google_font_body', 'shiword_google_font_post_title', 'shiword_google_font_post_content' )
		),
		'shiword_google_font_body' => array(
							'group'				=> 'fonts',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'for whole page', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_google_font_post_title' => array(
							'group'				=> 'fonts',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'for posts/pages title', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_google_font_post_content' => array(
							'group'				=> 'fonts',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'for posts/pages content', 'shiword' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_editor_style' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post editor style', 'shiword' ),
							'info'				=> __( "add style to the editor in order to write the post exactly how it will appear on the site", 'shiword' ),
							'req'				=> ''
		),
		'shiword_custom_bg' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'custom background', 'shiword' ),
							'info'				=>sprintf( __( "use the enhanced custom background page instead of the standard one. Disable it if the <a href=\"%s\">custom background page</a> works weird", 'shiword' ), get_admin_url() . 'themes.php?page=custom-background' ),
							'req'				=> ''
		),
		'shiword_custom_widgets' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'custom widgets', 'shiword' ),
							'info'				=> __( 'add a lot of new usefull widgets', 'shiword' ),
							'req'				=> ''
		),
		'shiword_mobile_css' => array(
							'group'				=> 'mobile',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'mobile support', 'shiword' ),
							'info'				=> __( "detect mobile devices and use a dedicated style. Disable it if you don't like it or you're already using a plugin for mobile support", 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_mobile_css_color' )
		),
		'shiword_mobile_css_color' => array(
							'group'				=> 'mobile',
							'type'				=> 'opt',
							'default'			=> 'dark',
							'options'			=> array( 'light', 'dark' ),
							'options_readable'	=> array( '<img src="' . get_template_directory_uri() . '/images/mobile-light.png" alt="light" />', '<img src="' . get_template_directory_uri() . '/images/mobile-dark.png" alt="dark" />' ),
							'description'		=> __( 'colors', 'shiword' ),
							'info'				=> __( "", 'shiword' ),
							'req'				=> '',
							'sub'				=> false
		),
		'shiword_I_like_it' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'I like it', 'shiword' ),
							'info'				=> __( 'show "like" badges beside the post content', 'shiword' ),
							'req'				=> '',
							'sub'				=> array( 'shiword_I_like_it_plus1', 'shiword_I_like_it_twitter', 'shiword_I_like_it_facebook', 'shiword_I_like_it_linkedin', 'shiword_I_like_it_stumbleupon', 'shiword_I_like_it_pinterest' )
		),
		'shiword_I_like_it_plus1' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> 'Google +1',
							'info'				=> '',
							'req'				=> 'shiword_I_like_it',
							'sub'				=> false
		),
		'shiword_I_like_it_twitter' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> 'Twitter',
							'info'				=> '',
							'req'				=> 'shiword_I_like_it',
							'sub'				=> false
		),
		'shiword_I_like_it_facebook' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> 'Facebook',
							'info'				=> '',
							'req'				=> 'shiword_I_like_it',
							'sub'				=> false
		),
		'shiword_I_like_it_linkedin' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> 'LinkedIn',
							'info'				=> '',
							'req'				=> 'shiword_I_like_it',
							'sub'				=> false
		),
		'shiword_I_like_it_stumbleupon' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> 'StumbleUpon',
							'info'				=> '',
							'req'				=> 'shiword_I_like_it',
							'sub'				=> false
		),
		'shiword_I_like_it_pinterest' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> 'Pinterest',
							'info'				=> __( 'visible ONLY in attachments', 'shiword' ),
							'req'				=> 'shiword_I_like_it',
							'sub'				=> false
		),
		'shiword_audio_player' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'swf audio player', 'shiword' ),
							'info'				=> __( 'embed an audio player before the post content for linked mp3 files', 'shiword' ),
							'req'				=> ''
		),
		'shiword_custom_css' => array(
							'group'				=> 'other',
							'type'				=> 'txtarea',
							'default'			=> '',
							'description'		=> __( 'custom CSS code', 'shiword' ),
							'info'				=> __( '<strong>For advanced users only</strong>: paste here your custom css code. it will be added after the defatult style', 'shiword' ) . ' (<a href="'. get_stylesheet_uri() .'" target="_blank">style.css</a>)',
							'req'				=> ''
		),
		'shiword_tbcred' => array(
							'group'				=> 'other',
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'theme credits', 'shiword' ),
							'info'				=> __( "please, don't hide theme credits. TwoBeers.net's authors reserve themselfs to give support only to those who recognize TwoBeers work, keeping TwoBeers.net credits visible on their site.", 'shiword' ),
							'req'				=> ''
		)
	);

	$shiword_coa = apply_filters( 'shiword_options_array', $shiword_coa );

	if ( $option == 'groups' )
		return $shiword_groups;
	elseif ( $option )
		return ( isset( $shiword_coa[$option] ) ) ? $shiword_coa[$option] : false ;
	else
		return $shiword_coa;

}

// retrive the required option. If the option ain't set, the default value is returned
if ( !function_exists( 'shiword_get_opt' ) ) {
	function shiword_get_opt( $opt ) {
		global $shiword_opt;

		if ( isset( $shiword_opt[$opt] ) ) return apply_filters( 'shiword_option_override', $shiword_opt[$opt], $opt );

		$defopt = shiword_get_coa( $opt );

		if ( ! $defopt ) return null;

		if ( ( $defopt['req'] == '' ) || ( shiword_get_opt( $defopt['req'] ) ) )
			return $defopt['default'];
		else
			return null;

	}
}

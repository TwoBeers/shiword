<?php
/**** begin theme hooks ****/
// Make theme available for translation
load_theme_textdomain( 'shiword', TEMPLATEPATH . '/languages' );
// Tell WordPress to run shiword_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'shiword_setup' );
// Register sidebars by running shiword_widgets_init() on the widgets_init hook
add_action( 'widgets_init', 'shiword_widgets_init' );
// Add the editor style
add_editor_style( 'css/editor-style.css' );
// Add stylesheets
add_action( 'wp_print_styles', 'shiword_stylesheet' );
// Add js animations
add_action( 'template_redirect', 'shiword_scripts' );
// Add custom category page
add_action( 'template_redirect', 'shiword_allcat' );
// Add custom menus
add_action( 'admin_menu', 'shiword_create_menu' );
// Custom filters
add_filter( 'the_content', 'shiword_content_replace' );
add_filter( 'excerpt_length', 'new_excerpt_length' );
add_filter( 'get_comment_author_link', 'sw_add_quoted_on' );
// Add custom login
add_action('login_head', 'my_custom_login_css');
/**** end theme hooks ****/

// Set the content width based on the theme's design
if ( !isset( $content_width ) ) {
	$content_width = 560;
}

//complete options array, with defaults values, description, infos and required option
$shiword_coa = array(
	'shiword_qbar' => array( 'default'=>1,'description'=>__( 'sliding menu', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_qbar_user' => array( 'default'=>1,'description'=>__( '-- user', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_qbar' ),
	'shiword_qbar_reccom' => array( 'default'=>1,'description'=>__( '-- recent comments', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_qbar' ),
	'shiword_qbar_cat' => array( 'default'=>1,'description'=>__( '-- categories','shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_qbar' ),
	'shiword_qbar_recpost' => array( 'default'=>1,'description'=>__( '-- recent posts', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_qbar' ),
	'shiword_xcont' => array( 'default'=>1,'description'=>__( 'content summary', 'shiword' ),'info'=>__( 'use the summary instead of content in main post index [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_pthumb' => array( 'default'=>0,'description'=>__( 'posts thumbnail', 'shiword' ),'info'=>__( '[default = disabled]', 'shiword' ),'req'=>'shiword_xcont' ),
	'shiword_rsideb' => array( 'default'=>1,'description'=>__( 'right sidebar', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_rsidebpages' => array( 'default'=>0,'description'=>__( '-- on pages', 'shiword' ),'info'=>__( 'show right sidebar on pages [default = disabled]', 'shiword' ),'req'=>'shiword_rsideb' ),
	'shiword_rsidebposts' => array( 'default'=>0,'description'=>__( '-- on posts', 'shiword' ),'info'=>__( 'show right sidebar on posts [default = disabled]', 'shiword' ),'req'=>'shiword_rsideb' ),
	'shiword_jsani' => array( 'default'=>1,'description'=>__( 'javascript animations', 'shiword' ),'info'=>__( 'try disable animations if you encountered problems with javascript [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_sticky' => array( 'default'=>0,'description'=>__( '-- sticky post slider', 'shiword' ),'info'=>__( 'slideshow for your sticky posts [default = disabled]', 'shiword' ),'req'=>'shiword_jsani' ),
	'shiword_navlinks' => array( 'default'=>0,'description'=>__( 'classic navigation links', 'shiword' ),'info'=>__( "show the classic navigation links (paged posts navigation, next/prev post, etc). Note: the same links are already located in the easy-navigation bar [default = disabled]", 'shiword' ),'req'=>'' ),
	'shiword_tbcred' => array( 'default'=>1,'description'=>__( 'theme credits', 'shiword' ),'info'=>__( "please, don't hide theme credits [default = enabled]", 'shiword' ),'req'=>'' )
);

//load options in $shiword_opt variable, globally retrieved in php files
$shiword_opt = get_option( 'shiword_options' );
$shiword_colors = shiword_get_colors();


function shiword_get_default_colors($type) {
	// Holds default outside colors
	$shiword_default_device_bg = array(
		'device_image' => '',
		'device_color' => '#000',
		'device_opacity' => '100',
		'device_textcolor' => '#fff',
		'device_button' => '#ff8d39'
	);
	// Holds default inside colors
	$shiword_default_device_colors = array(
		'main3' => '#21759b',
		'main4' => '#ff8d39',
		'menu1' => '#21759b',
		'menu2' => '#cccccc',
		'menu3' => '#262626',
		'menu4' => '#ffffff',
		'menu5' => '#ff8d39',
		'menu6' => '#cccccc',
	);
	if ( $type == 'out') { return $shiword_default_device_bg; }
	elseif ( $type == 'in') { return $shiword_default_device_colors; }
	else { return array_merge( $shiword_default_device_bg , $shiword_default_device_colors ); }
}

//get theme version
if ( get_theme( 'Shiword' ) ) {
	$current_theme = get_theme( 'Shiword' );
	$shiword_version = $current_theme['Version'];
} else {
	$shiword_version = "";
}

// print a reminder message for set the options after the theme is installed
function sw_setopt_admin_notice() {
	echo '<div class="updated"><p><strong>' . sprintf( __( "Shiword theme says: \"Don't forget to set <a href=\"%s\">my options</a> and the header image!\"", 'shiword' ), get_admin_url() . 'themes.php?page=tb_shiword_functions' ) . '</strong></p></div>';
}
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == "themes.php" ) {
	add_action( 'admin_notices', 'sw_setopt_admin_notice' );
}

// check if in preview mode or not
$is_sw_printpreview = false;
if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) { //print preview
	$is_sw_printpreview = true;
}

// check if is "all category" page
$shiword_is_allcat_page = false;
if( isset( $_GET['allcat'] ) && ( md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ) ) {
	$shiword_is_allcat_page = true;
}

function shiword_widgets_init() {
	// Area 1, located at the top of the sidebar.
	global $shiword_opt;
	if ( !isset( $shiword_opt['shiword_rsideb'] ) || $shiword_opt['shiword_rsideb'] == 1 ) {
		register_sidebar( array(
			'name' => __( 'Sidebar Widget Area', 'shiword' ),
			'id' => 'primary-widget-area',
			'description' => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );
	};

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer Widget Area #1', 'shiword' ),
		'id' => 'first-footer-widget-area',
		'description' => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="fwa_title">',
		'after_title' => '</div>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer Widget Area #2', 'shiword' ),
		'id' => 'second-footer-widget-area',
		'description' => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="fwa_title">',
		'after_title' => '</div>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer Widget Area #3', 'shiword' ),
		'id' => 'third-footer-widget-area',
		'description' => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="fwa_title">',
		'after_title' => '</div>',
	) );

	// Area 6, located in the header. Empty by default.
	register_sidebar( array(
		'name' => __( 'Header Widget Area', 'shiword' ),
		'id' => 'header-widget-area',
		'description' => __( 'Tips: Don&apos;t drag too much widgets here. Use small &quot;graphical&quot; widgets (eg icons, buttons, the search form, etc.)', 'shiword' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="fwa_title">',
		'after_title' => '</div>',
	) );

}

// Add stylesheets to page
function shiword_stylesheet(){
	global $shiword_version, $is_sw_printpreview;
	//shows print preview / normal view
	if ( $is_sw_printpreview ) { //print preview
		wp_enqueue_style( 'print-style-preview', get_template_directory_uri() . '/css/print.css', false, $shiword_version, 'screen' );
		wp_enqueue_style( 'general-style-preview', get_template_directory_uri() . '/css/print_preview.css', false, $shiword_version, 'screen' );
	} else { //normal view
		wp_enqueue_style( 'general-style', get_stylesheet_uri(), false, $shiword_version, 'screen' );
	}
	//print style
	wp_enqueue_style( 'print-style', get_template_directory_uri() . '/css/print.css', false, $shiword_version, 'print' );
}

// add scripts
function shiword_scripts(){
	global $shiword_opt, $is_sw_printpreview, $shiword_version;
	if ($shiword_opt['shiword_jsani'] == 1) {
		if ( !$is_sw_printpreview ) { //script not to be loaded in print preview
			wp_enqueue_script( 'sw-animations', get_template_directory_uri().'/js/sw-animations.min.js',array('jquery'),$shiword_version, true ); //shiword js
			if ( $shiword_opt['shiword_sticky'] == 1 ) wp_enqueue_script( 'sw-sticky-slider', get_template_directory_uri().'/js/sw-sticky-slider.min.js',array('jquery'),$shiword_version , false );
		}
	}
}

// show all categories list (redirect to allcat.php if allcat=y)
function shiword_allcat () {
	global $shiword_is_allcat_page;
	if( $shiword_is_allcat_page ) {
		get_template_part( 'allcat' );
		exit;
	}
}

// Get Recent Comments
function get_shiword_recentcomments() {
	$comments = get_comments( 'status=approve&number=10&type=comment' ); // valid type values (not documented) : 'pingback','trackback','comment'
	if ( $comments ) {
		foreach ( $comments as $comment ) {
			$post_title = get_the_title( $comment->comment_post_ID );
			if ( strlen( $post_title ) > 35 ) { //shrink the post title if > 35 chars
				$post_title_short = substr( $post_title,0,35 ) . '&hellip;';
			} else {
				$post_title_short = $post_title;
			}
			if ( $post_title_short == "" ) {
				$post_title_short = __( '(no title)', 'shiword' );
			}
			$com_auth = $comment->comment_author;
			if ( post_password_required( get_post( $comment->comment_post_ID ) ) ) {
				$com_auth = __( 'someone','shiword' );
			} else {
				$com_auth = $comment->comment_author;
			}
			if ( strlen( $com_auth ) > 35 ) {  //shrink the comment author if > 35 chars
				$com_auth = substr( $com_auth,0,35 ) . '&hellip;';
			}
		    echo '<li>'. $com_auth . ' ' . __( 'on', 'shiword' ) . ' <a href="' . get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID . '">' . $post_title_short . '</a><div class="preview">';
		if ( post_password_required( get_post( $comment->comment_post_ID ) ) ) {
			echo '[' . __( 'No preview: this is a comment of a protected post', 'shiword' ) . ']';
		} else {
			$comment_string = improved_trim_excerpt( $comment->comment_content );
			echo $comment_string;
		}
			echo '</div></li>';
		}
	} else {
		echo '<li>' . __( 'No comments yet.', 'shiword' ) . '</li>';
	}
}

// Get Recent Entries
function get_shiword_recententries() {
	$lastposts = get_posts( 'numberposts=10' );
	foreach( $lastposts as $post ) {
		setup_postdata( $post );
		$post_title = esc_html( $post->post_title );
		if ( strlen( $post_title ) > 35 ) { //shrink the post title if > 35 chars
			$post_title_short = substr( $post_title,0,35 ) . '&hellip;';
		} else {
			$post_title_short = $post_title;
		}
		if ( $post_title_short == "" ) {
			$post_title_short = __( '(no title)', 'shiword' );
		}
		$post_auth = get_the_author();
		if ( strlen( $post_auth ) > 35 ) { //shrink the post author if > 35 chars
			$post_auth = substr( $post_auth,0,35 ) . '&hellip;';
		}
		echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . sprintf( __( 'by %s', 'shiword' ), $post_auth ) . '<div class="preview">';
		if ( post_password_required( $post ) ) {
			echo '<img class="alignleft wp-post-image"  height="50" width="50" src="' . get_template_directory_uri() . '/images/thumb_50.png" alt="thumb" title="' . $post_title_short . '" />';
			echo '[' . __('No preview: this is a protected post', 'shiword' ) . ']';
		} else {
			if( has_post_thumbnail() ) {
				the_post_thumbnail( array( 50,50 ), array( 'class' => 'alignleft' ) );
			} else {
				echo '<img class="alignleft wp-post-image"  height="50" width="50" src="' . get_template_directory_uri() . '/images/thumb_50.png" alt="thumb" title="' . $post_title_short . '" />';
			}
			echo improved_trim_excerpt( $post->post_content );
		}
		echo '</div></li>';
	}
}

// Get Categories List (with posts related)
function get_shiword_categories_wpr() {
	$args=array(
		'orderby' => 'count',
		'number' => 10,
		'order' => 'DESC'
	);
	$categories = get_categories( $args );
	foreach( $categories as $category ) {
		echo '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( 'View all posts in %s', 'shiword' ), $category->name ) . '" >' . $category->name . '</a> (' . $category->count . ')<div class="cat_preview"><div class="mentit">' . __( 'Recent Posts', 'shiword' ) . '</div><ul class="solid_ul">';
		$tmp_cat_ID = $category->cat_ID;
		$post_search_args = array(
			'numberposts' => 5,
			'category' => $tmp_cat_ID
			);
		$lastcatposts = get_posts( $post_search_args ); //get the post list for each category
		foreach( $lastcatposts as $post ) {
			setup_postdata( $post );
			$post_title = esc_html( $post->post_title );
			if ( strlen( $post_title ) > 35 ) { //shrink the post title if > 35 chars
				$post_title_short = substr( $post_title,0,35 ) . '&hellip;';
			} else {
				$post_title_short = $post_title;
			}
			if ($post_title_short == "") {
				$post_title_short = __( '(no title)', 'shiword' );
			}
			$post_auth = get_the_author();
			if ( strlen( $post_auth ) > 35 ) { //shrink the post author if > 35 chars
				$post_auth = substr( $post_auth,0,35 ) . '&hellip;';
			}
			echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . sprintf( __( 'by %s', 'shiword' ), $post_auth ) . '</li>';
		}
		echo '</ul></div></li>';
	}
}

// Pages Menu
function shiword_pages_menu() {
	echo '<ul id="mainmenu">';
	wp_list_pages( 'sort_column=menu_order&title_li=' ); // menu-order sorted
	echo '</ul>';
}

// pages navigation links
function shiword_page_navi($this_page_id) {
	$pages = get_pages( array('sort_column' => 'menu_order') ); // get the menu-ordered list of the pages
	$page_links = array();
	foreach ($pages as $k => $pagg) {
		if ( $pagg->ID == $this_page_id ) { // we are in this $pagg
			if ( $k == 0 ) { // is first page
				$page_links['next']['link'] = get_page_link($pages[1]->ID);
				$page_links['next']['title'] = $pages[1]->post_title;
				if ( $page_links['next']['title'] == '' ) $page_links['next']['title'] = __( '(no title)', 'shiword' );
			} elseif ( $k == ( count( $pages ) -1 ) ) { // is last page
				$page_links['prev']['link'] = get_page_link($pages[$k - 1]->ID);
				$page_links['prev']['title'] = $pages[$k - 1]->post_title;
				if ( $page_links['prev']['title'] == '' ) $page_links['prev']['title'] = __( '(no title)', 'shiword' );
			} else {
				$page_links['next']['link'] = get_page_link($pages[$k + 1]->ID);
				$page_links['next']['title'] = $pages[$k + 1]->post_title;
				if ( $page_links['next']['title'] == '' ) $page_links['next']['title'] = __( '(no title)', 'shiword' );
				$page_links['prev']['link'] = get_page_link($pages[$k - 1]->ID);
				$page_links['prev']['title'] = $pages[$k - 1]->post_title;
				if ( $page_links['prev']['title'] == '' ) $page_links['prev']['title'] = __( '(no title)', 'shiword' );
			}
		}
	}
	return $page_links;
}

// page hierarchy
function shiword_multipages(){
	global $post;
	$args = array(
		'post_type' => 'page',
		'post_parent' => $post->ID
		);
	$childrens = get_posts( $args ); // retrieve the child pages
	$the_parent_page = $post->post_parent; // retrieve the parent page
	$has_herarchy = false;

	if ( ( $childrens ) || ( $the_parent_page ) ){ // add the hierarchy metafield ?>
		<div class="metafield">
			<div class="metafield_trigger mft_hier" style="right: 40px; width:16px"> </div>
			<div class="metafield_content">
				<?php
				echo __( 'This page has hierarchy', 'shiword' ) . ' - ';
				if ( $the_parent_page ) {
					$the_parent_link = '<a href="' . get_permalink( $the_parent_page ) . '" title="' . esc_attr( strip_tags( get_the_title( $the_parent_page ) ) ) . '">' . get_the_title( $the_parent_page ) . '</a>';
					echo __( 'Parent page: ', 'shiword' ) . $the_parent_link ; // echoes the parent
				}
				if ( ( $childrens ) && ( $the_parent_page ) ) { echo ' - '; } // if parent & child, echoes the separator
				if ( $childrens ) {
					$the_child_list = '';
					foreach ( $childrens as $children ) {
						$the_child_list[] = '<a href="' . get_permalink( $children ) . '" title="' . esc_attr( strip_tags( get_the_title( $children ) ) ) . '">' . get_the_title( $children ) . '</a>';
					}
					$the_child_list = implode( ', ' , $the_child_list );
					echo __( 'Child pages: ', 'shiword' ) . $the_child_list; // echoes the childs
				}
				?>
			</div>
		</div>
		<?php
		$has_herarchy = true;
	}
	return $has_herarchy;
}

//add a fix for embed videos overlaing quickbar
function shiword_content_replace( $content ) {
	$content = str_replace( '<param name="allowscriptaccess" value="always">', '<param name="allowscriptaccess" value="always"><param name="wmode" value="transparent">', $content );
	$content = str_replace( '<embed ', '<embed wmode="transparent" ', $content );
	return $content;
}

// create custom theme settings menu
function shiword_create_menu() {
	//create new top-level menu - Theme Options
	$topage = add_theme_page( __( 'Theme Options', 'shiword' ), __( 'Theme Options', 'shiword' ), 'edit_theme_options', 'tb_shiword_functions', 'edit_shiword_options' );
	//create new top-level menu - Slideshow
	$slidepage = add_theme_page( __( 'Slideshow', 'shiword' ), __( 'Slideshow', 'shiword' ), 'edit_theme_options', 'tb_shiword_slideshow', 'edit_shiword_slideshow' );
	//call register settings function
	add_action( 'admin_init', 'register_tb_sw_settings' );
	//call custom stylesheet function
	add_action( 'admin_print_styles-' . $topage, 'shiword_theme_options_style' );
	add_action( 'admin_print_styles-' . $slidepage, 'shiword_slide_options_style' );
	add_action( 'admin_print_scripts-' . $slidepage, 'shiword_slide_options_script' );
}

function register_tb_sw_settings() {
	//register general settings
	register_setting( 'shiw_settings_group', 'shiword_options', 'shiword_sanitize_options' );
	//register slideshow settings
	register_setting( 'shiw_slideshow_group', 'shiword_slideshow', 'shiword_sanitize_slideshow'  );
	//register colors settings
	register_setting( 'shiw_colors_group', 'shiword_colors'  );
}

function shiword_theme_options_style() {
	//add custom stylesheet
	echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/theme-options.css" />' . "\n";
}

function shiword_slide_options_style() {
	//add custom stylesheet
	echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/slide-options.css" />' . "\n";
}

function shiword_slide_options_script() {
	global $shiword_version;
	wp_enqueue_script( 'sw_otp_script', get_template_directory_uri().'/js/sw-otp-script.dev.js',array('jquery'),$shiword_version, true ); //shiword js
}

// sanitize options value
function shiword_sanitize_options( $input ){
	global $shiword_coa;
	// check updated values
	foreach ( $input as $key => $val ) {
		$input[$key] = ( $input[$key] == 1 ? 1 : 0 );
	}
	// check for updated values and return 0 for disabled ones <- index notice prevention
	foreach ( $shiword_coa as $key => $val ) {
		if( !isset( $input[$key] ) ) $input[$key] = 0;
	}
	// check for required options
	foreach ( $shiword_coa as $key => $val ) {
		if ( $shiword_coa[$key]['req'] != '' ) { if ( $input[$shiword_coa[$key]['req']] == 0 ) $input[$key] = 0; }
	}
	return $input;
}

// the option page
function edit_shiword_options() {
  if ( !current_user_can( 'edit_theme_options' ) ) {
    wp_die( __( 'You do not have sufficient permissions to access this page.', 'shiword' ) );
  }
	global $shiword_coa, $shiword_opt;
	
	// if options are empty, sets the default values
	if ( empty( $shiword_opt ) || !isset( $shiword_opt ) ) {
		foreach ( $shiword_coa as $key => $val ) {
			$shiword_opt[$key] = $shiword_coa[$key]['default'];
		}
		update_option( 'shiword_options' , $shiword_opt );
	}
	
	// return options save message
	if ( isset( $_REQUEST['updated'] ) ) {
		echo '<div id="message" class="updated"><p><strong>' . __( 'Options saved.', 'shiword' ) . '</strong></p></div>';
	}

	?>
	<div class="wrap">
		<div class="icon32" id="icon-themes"><br></div>
		<h2><?php echo get_current_theme() . ' - ' . __( 'Theme Options', 'shiword' ); ?></h2>
		<div id="tabs-container">
			<ul id="selector">
				<li id="shiword-options-li">
					<a href="#shiword-options" onClick="shiwordSwitchClass('shiword-options'); return false;"><span class="wp-menu-image" style="background-image: url('<?php echo get_admin_url() . 'images/menu.png' ?>')"> </span><?php _e( 'theme features' , 'shiword' ); ?></a>
				</li>
				<li id="shiword-infos-li">
					<a href="#shiword-infos" onClick="shiwordSwitchClass('shiword-infos'); return false;"><span class="wp-menu-image" style="background-image: url('<?php echo get_admin_url() . 'images/menu.png' ?>')"> </span><?php _e( 'About', 'shiword' ); ?></a>
				</li>
			</ul>
			<div class="clear"></div>
			<div id="shiword-options">
				<form method="post" action="options.php">
					<?php settings_fields( 'shiw_settings_group' ); ?>
					<div id="stylediv">
						<table style="border-collapse: collapse; width: 100%;background-color:#fff;">
							<tr>
								<th><?php _e( 'name' , 'shiword' ); ?></th>
								<th><?php _e( 'status' , 'shiword' ); ?></th>
								<th><?php _e( 'description' , 'shiword' ); ?></th>
								<th><?php _e( 'require' , 'shiword' ); ?></th>
							</tr>
						<?php foreach ($shiword_coa as $key => $val) { ?>
							<tr>
								<td style="width: 220px;font-weight:bold;border-right:1px solid #ccc;"><?php echo $shiword_coa[$key]['description']; ?></td>
								<td style="width: 20px;border-right:1px solid #ccc;text-align:center;">
									<input name="shiword_options[<?php echo $key; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $shiword_opt[$key] ); ?> />
								</td>
								<td style="font-style:italic;border-right:1px solid #ccc;"><?php echo $shiword_coa[$key]['info']; ?></td>
								<td><?php if ( $shiword_coa[$key]['req'] != '' ) echo $shiword_coa[$shiword_coa[$key]['req']]['description']; ?></td>
							</tr>
						<?php }	?>
						</table>
					</div>
					<div>
						<input type="hidden" name="shiword_options[hidden_opt]" value="default" />
						<input class="button" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'shiword' ); ?>" />
						<a style="font-size: 10px; text-decoration: none; margin-left: 10px; cursor: pointer;" href="themes.php?page=functions" target="_self"><?php _e( 'Undo Changes' , 'shiword' ); ?></a>
					</div>
				</form>
			</div>
			<div id="shiword-infos">
				<?php esc_attr( get_template_part( 'readme' ) ); ?>
			</div>
			<div class="clear"></div>
		</div>
				<div class="stylediv" style="clear: both; text-align: center; border: 1px solid #ccc;">
					<small>
						<?php _e( 'If you like/dislike this theme, or if you encounter any issues, please let us know it.', 'shiword' ); ?><br />
						<a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/shiword' ); ?>" title="Shiword theme" target="_blank"><?php _e( 'Leave a feedback', 'shiword' ); ?></a>
					</small>
				</div>
				<div class="stylediv" style="clear: both; text-align: center; border: 1px solid #ccc; margin-top: 10px;">
					<small>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/temi-wp/wordpress-themes-translations' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
				</div>
		<script type="text/javascript">
			/* <![CDATA[ */
			function shiwordSwitchClass(a) { // simple animation for option tabs
				switch(a) {
					case 'shiword-options':
						document.getElementById('shiword-infos').className = 'tab-hidden';
						document.getElementById('shiword-options').className = '';
						document.getElementById('shiword-options-li').className = 'tab-selected';
						document.getElementById('shiword-infos-li').className = '';
					break;
					case 'shiword-infos':
						document.getElementById('shiword-infos').className = '';
						document.getElementById('shiword-options').className = 'tab-hidden';
						document.getElementById('shiword-options-li').className = '';
						document.getElementById('shiword-infos-li').className = 'tab-selected';
					break;
				}
			}
			document.getElementById('shiword-infos').className = 'tab-hidden';
			document.getElementById('shiword-options-li').className = 'tab-selected';
			/* ]]> */
		</script>
	</div>
	<?php
}

function shiword_sanitize_slideshow( $input ){
	//check for numeric value
	foreach ( $input as $key => $val ) {
		if( is_numeric( $val ) ) {
			$input[$key] = $val;
		} else {
			 unset( $input[$key] );
		}
	}
	return $input;
}

// the slideshow admin panel - here you can select posts to be included in slideshow
function edit_shiword_slideshow() {
	$shiword_options = get_option( 'shiword_slideshow' );
	if ( isset( $_REQUEST['updated'] ) ) {
		//return options save message
		echo '<div id="message" class="updated"><p><strong>' . __( 'Options saved.', 'shiword' ) . '</strong></p></div>';
	}
?>
	<script type="text/javascript">
		/* <![CDATA[ */
		function shiwordSlideSwitchClass(a) { // simple animation for option tabs
			switch(a) {
				case 'shiwordSlide-posts':
					document.getElementById('shiwordSlide-pages').className = 'tab-hidden';
					document.getElementById('shiwordSlide-posts').className = '';
					document.getElementById('shiwordSlide-pages-li').className = '';
					document.getElementById('shiwordSlide-posts-li').className = 'tab-selected';
				break;
				case 'shiwordSlide-pages':
					document.getElementById('shiwordSlide-pages').className = '';
					document.getElementById('shiwordSlide-posts').className = 'tab-hidden';
					document.getElementById('shiwordSlide-pages-li').className = 'tab-selected';
					document.getElementById('shiwordSlide-posts-li').className = '';
				break;
			}
		}
		/* ]]> */
	</script>
	<div class="wrap">
		<div class="icon32" id="icon-themes"><br></div>
		<h2><?php echo get_current_theme() . ' - ' . __( 'Slideshow', 'shiword' ); ?></h2>
		<div style="margin-top: 20px;">
			<?php _e( 'Select posts or pages to be displaied in the index-page slideshow box.<br />Items will be ordered as display here.', 'shiword' ); ?>
		</div>
		<div>
			<form method="post" action="options.php">
				<?php settings_fields( 'shiw_slideshow_group' ); ?>

				<div id="tabs-container">
					<ul id="selector">
						<li id="shiwordSlide-posts-li">
							<a href="#shiwordSlide-posts" onClick="shiwordSlideSwitchClass('shiwordSlide-posts'); return false;"><span class="wp-menu-image" style="background-image: url('<?php echo get_admin_url() . 'images/menu.png' ?>')"> </span><?php _e( 'Posts', 'shiword' ); ?></a>
						</li>
						<li id="shiwordSlide-pages-li">
							<a href="#shiwordSlide-pages" onClick="shiwordSlideSwitchClass('shiwordSlide-pages'); return false;"><span class="wp-menu-image" style="background-image: url('<?php echo get_admin_url() . 'images/menu.png' ?>')"> </span><?php _e( 'Pages', 'shiword' ); ?></a>
						</li>
					</ul>
					<div class="jump_bottom">
						<small>
							<a href="#shiwordSlide-bottom_ref" title="<?php _e( 'Remember to click the Save Changes button at the bottom of the screen for new settings to take effect.', 'shiword' ); ?>" ><?php _e( 'Save', 'shiword' ); ?></a>
						</small>
					</div>
					<div class="clear"></div>

					<?php $lastposts = get_posts( 'post_type=post&numberposts=-1&orderby=date' ); ?>

					<div id="shiwordSlide-posts">
						<table cellspacing="0" class="widefat post fixed">
							<thead>
								<tr>
									<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
									<th style="" class="manage-column column-title" id="title" scope="col"><?php _e( 'Title', 'shiword' ); ?></th>
									<th style="" class="manage-column column-categories" id="categories" scope="col"><?php _e( 'Categories', 'shiword' ); ?></th>
									<th style="" class="manage-column column-date" id="date" scope="col"><?php _e( 'Date', 'shiword' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $lastposts as $post ) {
									$post_title = esc_html( $post->post_title );
									if ( $post_title == "" ) {
										$post_title = __( '(no title)', 'shiword' );
									}
									if( !isset( $shiword_options[$post->ID] ) ) $shiword_options[$post->ID] = 0;
									?>
									<tr class="sw_post_row">
										<th class="check-column" scope="row">
											<input name="shiword_slideshow[<?php echo $post->ID; ?>]" value="<?php echo $post->ID; ?>" type="checkbox" class="" <?php checked( $post->ID , $shiword_options[$post->ID] ); ?> />
										</th>
										<td class="post-title column-title">
											<a class="row-title" href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a>
										</td>
										<td class="categories column-categories">
											<?php
											foreach( ( get_the_category( $post->ID ) ) as $post_cat ) {
												echo $post_cat->name . ', ';
											}
											?>
										</td>
										<td class="date column-date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $post->post_date_gmt ) );  ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>

					<?php $lastpages = get_posts( 'post_type=page&numberposts=-1&orderby=menu_order' ); ?>

					<div id="shiwordSlide-pages">
						<table cellspacing="0" class="widefat post fixed">
							<thead>
								<tr>
									<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
									<th style="" class="manage-column column-title" id="title" scope="col"><?php _e( 'Title', 'shiword' ); ?></th>
									<th style="" class="manage-column column-date" id="date" scope="col"><?php _e( 'Date', 'shiword' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $lastpages as $page ) {
									$page_title = esc_html( $page->post_title );
									if ( $page_title == "" ) {
										$page_title = __( '(no title)', 'shiword' );
									}
									if( !isset( $shiword_options[$page->ID] ) ) $shiword_options[$page->ID] = 0;
									?>
									<tr>
										<th class="check-column" scope="row">
											<input name="shiword_slideshow[<?php echo $page->ID; ?>]" value="<?php echo $page->ID; ?>" type="checkbox" class="" <?php checked( $page->ID , $shiword_options[$page->ID] ); ?> />
										</th>
										<td class="post-title column-title">
											<a class="row-title" href="<?php echo get_permalink( $page->ID ); ?>" title="<?php echo $page_title; ?>"><?php echo $page_title; ?></a>
										</td>
										<td class="date column-date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $page->post_date_gmt ) );  ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>

					<div class="clear"></div>
				</div>
				<div id="shiwordSlide-bottom_ref" style="clear: both; height: 1px;"> </div>
				<p style="float:left; clear: both;">
					<input class="button" type="submit" name="Submit" value="<?php _e( 'Save Changes', 'shiword' ); ?>" />
					<a style="font-size: 10px; text-decoration: none; margin-left: 10px; cursor: pointer;" href="<?php echo get_admin_url() . 'themes.php?page=tb_shiword_slideshow'; ?>" target="_self"><?php _e( 'Undo Changes' , 'shiword' ); ?></a>
				</p>
			</form>
		</div>
		<script type="text/javascript">
			/* <![CDATA[ */
			document.getElementById('shiwordSlide-pages').className = 'tab-hidden';
			document.getElementById('shiwordSlide-posts-li').className = 'tab-selected';
			/* ]]> */
		</script>
	</div>

<?php 
}

// display a slideshow for the selected posts
function sw_sticky_slider() {
	$shiword_options = get_option( 'shiword_slideshow' ); //get the selected posts list
	if ( !isset( $shiword_options ) || empty( $shiword_options ) ) return; // if no post is selected, exit
	$posts_string = 'include=' . implode( "," , $shiword_options ) . '&post_type=any'; // generate the 'include' string for posts
	$ss_posts = get_posts( $posts_string ); // get all the selected posts
	?>
	<div id="sw_slider-wrap">
		<div id="sw_sticky_slider">
			<?php foreach( $ss_posts as $post ) {
				setup_postdata( $post );
				$post_title = esc_html( $post->post_title );
				if ( $post_title == "" ) {
					$post_title = __( '(no title)', 'shiword' );
				} ?>
				<div class="sss_item">
					<div class="sss_inner_item">
						<a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo $post_title; ?>">
							<?php if( has_post_thumbnail() ) :
								the_post_thumbnail( array( 120,120 ), array( 'class' => 'alignleft' ) );
							else :
								echo '<img class="alignleft wp-post-image"  height="120" width="120" src="' . get_template_directory_uri() . '/images/thumb_120.png" alt="thumb" title="' . $post_title . '" />';
							endif; ?>
						</a>
						<div style="padding-left: 130px;">
							<h2 class="storytitle"><a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a></h2> <?php echo __( 'by', 'shiword' ) . " " . get_the_author(); ?>
							<div style="font-size:12px">
								<?php the_excerpt(); ?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php add_action( 'wp_footer', 'init_sticky_slider' ); //include the initialize code in footer
}

function init_sticky_slider() { ?>
	<script type='text/javascript'>
	// <![CDATA[
		jQuery('#sw_sticky_slider').sw_sticky_slider({
			speed : 2500,
			pause : 2000
		})
	// ]]>
	</script>
<?php 
}

if ( !function_exists( 'shiword_setup' ) ) {
	function shiword_setup() {

		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Theme uses wp_nav_menu() in one location
		register_nav_menus( array( 'primary' => __( 'Main Navigation Menu', 'shiword' ) ) );

		// This theme allows users to set the device appearance
		add_custom_device_image();

		// Your changeable header business starts here
		define( 'HEADER_TEXTCOLOR', '404040' );
		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		define( 'HEADER_IMAGE', '%s/images/headers/green.jpg' );

		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to shiword_header_image_width and shiword_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH', 850 );
		define( 'HEADER_IMAGE_HEIGHT', 100 );

		// Don't support text inside the header image.
		define( 'NO_HEADER_TEXT', true );

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See shiword_admin_header_style(), below.
		add_custom_image_header( 'shiword_header_style', 'shiword_admin_header_style' );

		// ... and thus ends the changeable header business.

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'green' => array(
				'url' => '%s/images/headers/green.jpg',
				'thumbnail_url' => '%s/images/headers/green_thumbnail.jpg',
				'description' => 'green'
			),
			'black' => array(
				'url' => '%s/images/headers/black.jpg',
				'thumbnail_url' => '%s/images/headers/black_thumbnail.jpg',
				'description' => 'black'
			),
			'brown' => array(
				'url' => '%s/images/headers/brown.jpg',
				'thumbnail_url' => '%s/images/headers/brown_thumbnail.jpg',
				'description' => 'brown'
			),
			'blue' => array(
				'url' => '%s/images/headers/blue.jpg',
				'thumbnail_url' => '%s/images/headers/blue_thumbnail.jpg',
				'description' => 'blue'
			)
		) );
		register_default_device_images( array(
			'green' => array(
				'url' => '%s/images/device/white.png',
				'description' => 'white'
			),
			'black' => array(
				'url' => '%s/images/device/black.png',
				'description' => 'black'
			),
			'pink' => array(
				'url' => '%s/images/device/pink.png',
				'description' => 'pink'
			),
			'blue' => array(
				'url' => '%s/images/device/blue.png',
				'description' => 'blue'
			)
		) );
	}
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'shiword_admin_header_style' ) ) {
	function shiword_admin_header_style() {
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/custom-header.css" />' . "\n";
	}
}

function hex2rgba($hex,$alpha) {
	$color = str_replace('#','',$hex);
	if ( $color == 'transparent' ) { 
		return 'transparent';
	} else {
		$rgba = 'rgba(' . hexdec(substr($color,0,2)) . ',' . hexdec(substr($color,2,2)) . ',' . hexdec(substr($color,4,2)) . ',' . round(intval($alpha) / 100, 3) . ')';
		return $rgba;
	}
}

// custom header image style - gets included in the site header
function shiword_header_style() {
	global $shiword_colors;
	$device_rgba = hex2rgba( $shiword_colors['device_color'], $shiword_colors['device_opacity']);
    ?>
<style type="text/css">
	#headerimg {
		background: transparent url('<?php esc_url ( header_image() ); ?>') right bottom repeat-y;
		<?php if ( get_theme_mod( 'header_image' , HEADER_IMAGE ) == '' ) echo 'display: none;'; ?>
	}
	#sw_body,
	#fixedfoot {
		background: <?php echo $device_rgba; ?> url('<?php echo $shiword_colors['device_image']; ?>') right top repeat;
	}
	#head {
		background: <?php echo $device_rgba; ?> url('<?php echo $shiword_colors['device_image']; ?>') right -0.7em repeat;
	}
	input[type="button"]:hover,
	input[type="submit"]:hover,
	input[type="reset"]:hover {
		border-color: <?php echo $shiword_colors['main4']; ?>;
	}
	#head a,
	#head .description,
	#statusbar {
		color: <?php echo $shiword_colors['device_textcolor']; ?>;
	}
	.menuitem:hover .menuitem_img,
	.minib_img:hover {
		border-color: <?php echo $shiword_colors['device_button']; ?>;
	}
	#maincontent a {
		color : <?php echo $shiword_colors['main3']; ?>;
	}
	#maincontent a:hover {
		color : <?php echo $shiword_colors['main4']; ?>;
	}
	#pages,
	#mainmenu > li.page_item > ul.children,
	#mainmenu > li.menu-item > ul.sub-menu,
	.minibutton .nb_tooltip,
	.menuback {
		background-color: <?php echo $shiword_colors['menu1']; ?>;
		border-color: <?php echo $shiword_colors['menu2']; ?>;
		color: <?php echo $shiword_colors['menu3']; ?>;
	}
	#pages a,
	#pages > li.page_item > ul.children a,
	#pages > li.menu-item > ul.sub-menu a,
	.menuback a {
		color: <?php echo $shiword_colors['menu4']; ?>;
	}
	#pages a:hover,
	#pages > li.page_item > ul.children a:hover,
	#pages > li.menu-item > ul.sub-menu a:hover,
	.minibutton .nb_tooltip a:hover,
	.menuback a:hover,
	#pages .current-menu-item > a,
	#pages .current_page_item > a,
	li.current_page_ancestor .hiraquo,
	#pages .current-cat > a,
	#pages a:hover,
	#pages .current-menu-item a:hover,
	#pages .current_page_item a:hover,
	#pages .current-cat a:hover {
		color: <?php echo $shiword_colors['menu5']; ?>;
	}
	.menu_sx > ul {
		border-right:1px solid <?php echo $shiword_colors['menu6']; ?>;
	}
	.menuback .mentit {
		color: <?php echo $shiword_colors['menu6']; ?>;
	}
	.letsstick .sticky {
		-moz-box-shadow: 0 0 8px <?php echo $shiword_colors['main3']; ?>;
		box-shadow: 0 0 8px <?php echo $shiword_colors['main3']; ?>;
		-webkit-box-shadow: 0 0 8px <?php echo $shiword_colors['main3']; ?>;
	}
	textarea:hover,
	input[type="text"]:hover,
	input[type="password"]:hover,
	textarea:focus,
	input[type="text"]:focus,
	input[type="password"]:focus {
		border:1px solid <?php echo $shiword_colors['main4']; ?>;
	}
</style>
<!-- InternetExplorer really sucks! -->
<!--[if lte IE 8]>
<style type="text/css">
	#sw_body,
	#fixedfoot {
		background: <?php echo $shiword_colors['device_color']; ?> url('<?php echo $shiword_colors['device_image']; ?>') right top repeat;
	}
	#head {
		background: <?php echo $shiword_colors['device_color']; ?> url('<?php echo $shiword_colors['device_image']; ?>') right -0.7em repeat;
	}
</style>
<![endif]-->
    <?php
}

//get the theme color values. uses default values if options are empty or unset
function shiword_get_colors() {

	/* Holds default colors. */
	$default_device_colors = shiword_get_default_colors('all');

	$shiword_colors = get_option( 'shiword_colors' );
	foreach ( $default_device_colors as $key => $val ) {
		if( ( !isset( $shiword_colors[$key] ) ) || empty( $shiword_colors[$key] ) ) {
			$shiword_colors[$key] = $default_device_colors[$key];
		}
	}
	return $shiword_colors;
}

//custom excerpt maker
function improved_trim_excerpt( $text ) {
	$text = apply_filters( 'the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );
	$text = preg_replace( '@<script[^>]*?>.*?</script>@si', '', $text );
	$text = strip_tags( $text, '<p>' );
	$text = preg_replace( '@<p[^>]*?>@si', '', $text );
	$text = preg_replace( '@</p>@si', '<br/>', $text );
	$excerpt_length = 50;
	$words = explode(' ', $text, $excerpt_length + 1);
	if ( count( $words ) > $excerpt_length ) {
		array_pop( $words );
		array_push( $words, '[...]' );
		$text = implode( ' ', $words );
	}
	return $text;
}

//set the excerpt lenght
function new_excerpt_length( $length ) {
	return 30;
}

//styles the login page
function my_custom_login_css() {
    echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/login.css" />' . "\n";
}

//add a default gravatar
if ( !function_exists( 'shiword_addgravatar' ) ) {
	function shiword_addgravatar( $avatar_defaults ) {
	  $myavatar = get_template_directory_uri() . '/images/user.png';
	  $avatar_defaults[$myavatar] = __( 'shiword Default Gravatar', 'shiword' );

	  return $avatar_defaults;
	}
	add_filter( 'avatar_defaults', 'shiword_addgravatar' );
}

// add 'quoted on' before trackback/pingback comments link
function sw_add_quoted_on( $return ) {
	global $comment;
	$text = '';
	if ( get_comment_type() != 'comment' ) {
		$text = '<span style="font-weight: normal;">' . __( 'quoted on', 'shiword' ) . ' </span>';
	}
	return $text . $return;
}

// Register a selection of default images to be displayed as device backgrounds by the custom device color admin UI. based on WP theme.php -> register_default_headers()
function register_default_device_images( $headers ) {
	global $sw_default_device_images;

	$sw_default_device_images = array_merge( (array) $sw_default_device_images , (array) $headers );
}

// Add callbacks for device color display. based on WP theme.php -> add_custom_image_header()
function add_custom_device_image() {
	if ( ! is_admin() )
		return;
	require_once( 'custom-device-color.php' );
	$GLOBALS['custom_device_color'] =& new Custom_device_color();
	add_action( 'admin_menu' , array(&$GLOBALS['custom_device_color'] , 'init' ));
}

// display a simple login form in quickbar
function shiword_mini_login() {
	$args = array(
		'redirect' => home_url(),
		'form_id' => 'sw-loginform',
		'id_username' => 'sw-user_login',
		'id_password' => 'sw-user_pass',
		'id_remember' => 'sw-rememberme',
		'id_submit' => 'sw-submit' );
	?>
	<li class="ql_cat_li">
		<a title="<?php _e( 'Log in', 'shiword' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in', 'shiword' ); ?></a>
		<div class="cat_preview" style="padding-left: 20px;">
			<div class="mentit"><?php _e( 'Log in', 'shiword' ); ?></div>
			<div id="sw_minilogin" class="solid_ul">
				<?php wp_login_form($args); ?>
				<a id="closeminilogin" href="#" style="display: none; margin-left:10px;"><?php _e( 'Close', 'shiword' ); ?></a>
			</div>
		</div>
	</li>

	<?php
}

// load the custom widgets module
get_template_part('widgets');

?>

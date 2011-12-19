<?php
/**** begin theme hooks ****/
// Make theme available for translation
load_theme_textdomain( 'shiword', TEMPLATEPATH . '/languages' );
// Tell WordPress to run shiword_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'shiword_setup' );
// Tell WordPress to run shiword_default_options()
add_action( 'admin_init', 'shiword_default_options' );
// Register sidebars by running shiword_widgets_init() on the widgets_init hook
add_action( 'widgets_init', 'shiword_widgets_init' );
// Add stylesheets
add_action( 'wp_enqueue_scripts', 'shiword_stylesheet' );
// Add js animations
add_action( 'wp_enqueue_scripts', 'shiword_scripts' );
// Add custom category page
add_action( 'template_redirect', 'shiword_allcat' );
// mobile support
add_action( 'template_redirect', 'shiword_mobile' );
// Add custom menus
add_action( 'admin_menu', 'shiword_create_menu' );
// Add the "quote" link
add_action( 'wp_footer', 'shiword_quote_scripts' );
// setup for audio player
add_action( 'wp_head', 'shiword_setup_player' );
// Custom filters
add_filter( 'the_content', 'shiword_content_replace' );
add_filter( 'excerpt_length', 'shiword_excerpt_length' );
add_filter( 'get_comment_author_link', 'shiword_add_quoted_on' );
add_filter( 'user_contactmethods','shiword_new_contactmethods',10,1 );
add_filter( 'img_caption_shortcode', 'shiword_img_caption_shortcode', 10, 3 );
add_filter( 'avatar_defaults', 'shiword_addgravatar' );
add_filter( 'wp_nav_menu_items', 'shiword_new_nav_menu_items' );
add_filter( 'wp_list_pages', 'shiword_new_nav_menu_items' );
add_filter('get_comments_number', 'shiword_comment_count', 0);
// custom gallery shortcode function
remove_shortcode( 'gallery', 'gallery_shortcode' );
add_shortcode( 'gallery', 'shiword_gallery_shortcode' );
// Add custom login
add_action( 'login_head', 'shiword_custom_login_css' );
/**** end theme hooks ****/

// Set the content width based on the theme's design
if ( !isset( $content_width ) ) {
	$content_width = 560;
}

//complete options array, with defaults values, description, infos and required option
$shiword_coa = array(
	'shiword_qbar' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( 'sliding menu', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_qbar_user' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '-- user', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_qbar' ),
	'shiword_qbar_minilogin' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>0,'description'=>__( '---- mini login','shiword' ),'info'=>__( 'a small login form in the user panel [default = disabled]','shiword' ),'req'=>'shiword_qbar_user' ),
	'shiword_qbar_reccom' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '-- recent comments', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_qbar' ),
	'shiword_qbar_cat' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '-- categories','shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_qbar' ),
	'shiword_qbar_recpost' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '-- recent posts', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_qbar' ),
	'shiword_xcont' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( 'content summary', 'shiword' ),'info'=>__( 'use the summary instead of content in main post index [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_pthumb' => array( 'group' =>'content', 'type' =>'chk', 'default'=>0,'description'=>__( 'posts thumbnail', 'shiword' ),'info'=>__( '[default = disabled]', 'shiword' ),'req'=>'shiword_xcont' ),
	'shiword_xinfos' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( 'posts extra info', 'shiword' ),'info'=>__( 'show extra info (author, date, tags, etc) in posts overview [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_xinfos_static' => array( 'group' =>'content', 'type' =>'chk', 'default'=>0,'description'=>__( '-- static info', 'shiword' ),'info'=>__( 'show extra info as a static list (not dropdown animated) [default = disabled]', 'shiword' ),'req'=>'' ),
	'shiword_byauth' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post author', 'shiword' ),'info'=>__( 'show author on posts info [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_xinfos_date' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post date', 'shiword' ),'info'=>__( 'show date on posts info [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_xinfos_comm' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post comments', 'shiword' ),'info'=>__( 'show comments on posts/pages info [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_xinfos_tag' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post tags', 'shiword' ),'info'=>__( 'show tags on posts info [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_xinfos_cat' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post categories', 'shiword' ),'info'=>__( 'show categories on posts info [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_postformats' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( 'post formats support', 'shiword' ),'info'=>__( 'use the <a href="http://codex.wordpress.org/Post_Formats" target="_blank">Post Formats</a> new feature [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_postformat_aside' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- aside', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_postformats' ),
	'shiword_postformat_audio' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- audio', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_postformats' ),
	'shiword_postformat_gallery' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- gallery', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_postformats' ),
	'shiword_postformat_image' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- image', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_postformats' ),
	'shiword_postformat_link' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- link', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_postformats' ),
	'shiword_postformat_quote' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- quote', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_postformats' ),
	'shiword_postformat_status' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- status', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_postformats' ),
	'shiword_postformat_video' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- video', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'shiword_postformats' ),
	'shiword_quotethis' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'quote link', 'shiword' ),'info'=>__( 'show a link for easily add the selected text as a quote inside the comment form [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_rsideb' => array( 'group' =>'sidebar', 'type' =>'chk', 'default'=>1,'description'=>__( 'right sidebar', 'shiword' ),'info'=>__( '[default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_rsidebpages' => array( 'group' =>'sidebar', 'type' =>'chk', 'default'=>0,'description'=>__( '-- on pages', 'shiword' ),'info'=>__( 'show right sidebar on pages [default = disabled]', 'shiword' ),'req'=>'shiword_rsideb' ),
	'shiword_rsidebposts' => array( 'group' =>'sidebar', 'type' =>'chk', 'default'=>0,'description'=>__( '-- on posts', 'shiword' ),'info'=>__( 'show right sidebar on posts [default = disabled]', 'shiword' ),'req'=>'shiword_rsideb' ),
	'shiword_jsani' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'javascript animations', 'shiword' ),'info'=>__( 'try disable animations if you encountered problems with javascript [default = enabled]', 'shiword' ),'req'=>'' ),
	'shiword_sticky' => array( 'group' =>'slideshow', 'type' =>'chk', 'default'=>0,'description'=>__( 'slideshow', 'shiword' ),'info'=>sprintf( __( 'slideshow for your most important posts/pages. Select them <a href="%s">here</a> [default = disabled]', 'shiword' ), get_admin_url() . 'themes.php?page=tb_shiword_slideshow' ),'req'=>'shiword_jsani' ),
	'shiword_sticky_front' => array( 'group' =>'slideshow', 'type' =>'chk', 'default'=>1,'description'=>__( '-- in home/front page', 'shiword' ),'info'=>__( 'display slideshow in home/front page [default = enabled]', 'shiword' ),'req'=>'shiword_sticky' ),
	'shiword_sticky_pages' => array( 'group' =>'slideshow', 'type' =>'chk', 'default'=>0,'description'=>__( '-- in pages', 'shiword' ),'info'=>__( 'display slideshow in pages [default = disabled]', 'shiword' ),'req'=>'shiword_sticky' ),
	'shiword_sticky_posts' => array( 'group' =>'slideshow', 'type' =>'chk', 'default'=>0,'description'=>__( '-- in posts', 'shiword' ),'info'=>__( 'display slideshow in posts [default = disabled]', 'shiword' ),'req'=>'shiword_sticky' ),
	'shiword_sticky_over' => array( 'group' =>'slideshow', 'type' =>'chk', 'default'=>1,'description'=>__( '-- in posts overview', 'shiword' ),'info'=>__( 'display slideshow in posts overview (posts page, search results, archives, categories, etc.) [default = enabled]', 'shiword' ),'req'=>'shiword_sticky' ),
	'shiword_navlinks' => array( 'group' =>'other', 'type' =>'chk', 'default'=>0,'description'=>__( 'classic navigation links', 'shiword' ),'info'=>__( "show the classic navigation links (paged posts navigation, next/prev post, etc). Note: the same links are already located in the easy-navigation bar [default = disabled]", 'shiword' ),'req'=>'' ),
	'shiword_head_h' => array( 'group' =>'other', 'type' =>'sel', 'default'=>'100px', 'options'=>array( '0px', '100px', '150px', '200px', '250px', '300px' ), 'description'=>__( 'Header height','shiword' ),'info'=>__( '[default = 100px]','shiword' ),'req'=>'' ),
	'shiword_editor_style' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'post editor style', 'shiword' ),'info'=>__( "add style to the editor in order to write the post exactly how it will appear on the site [default = enabled]", 'shiword' ),'req'=>'' ),
	'shiword_mobile_css' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'mobile support', 'shiword' ),'info'=>__( "detect mobile devices and use a dedicated style [default = enabled]", 'shiword' ),'req'=>'' ),
	'shiword_tbcred' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'theme credits', 'shiword' ),'info'=>__( "please, don't hide theme credits [default = enabled]<br />TwoBeers.net's authors reserve themselfs to give support only to those who recognize TwoBeers work, keeping TwoBeers.net credits visible on their site.", 'shiword' ),'req'=>'' )
);

//load options in $shiword_opt variable, globally retrieved in php files
$shiword_opt = get_option( 'shiword_options' );
$shiword_colors = shiword_get_colors();

function shiword_get_default_colors($type) {
	// Holds default outside colors
	$shiword_default_device_bg = array(
		'device_image' => '',
		'device_color' => '#000000',
		'device_opacity' => '100',
		'device_textcolor' => '#ffffff',
		'device_button' => '#ff8d39',
		'device_button_style' => 'light'
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

// get theme version
if ( get_theme( 'Shiword' ) ) {
	$shiword_current_theme = get_theme( 'Shiword' );
	$shiword_version = $shiword_current_theme['Version'];
}

// check if is mobile browser
$sw_is_mobile_browser = shiword_mobile_device_detect();//true;

function shiword_mobile_device_detect() {
	global $shiword_opt;
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    if ( ( !isset( $shiword_opt['shiword_mobile_css'] ) || ( $shiword_opt['shiword_mobile_css'] == 1) ) && preg_match( '/(ipad|ipod|iphone|android|opera mini|blackberry|palm|symbian|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i' , $user_agent ) ) { // there were other words for mobile detecting but this is enought ;-)
		return true;
	} else {
		return false;
	}
}

// check and set default options 
function shiword_default_options() {
		global $shiword_coa, $shiword_current_theme;
		$shiword_opt = get_option( 'shiword_options' );

		// if options are empty, sets the default values
		if ( empty( $shiword_opt ) || !isset( $shiword_opt ) ) {
			foreach ( $shiword_coa as $key => $val ) {
				$shiword_opt[$key] = $shiword_coa[$key]['default'];
			}
			$shiword_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'shiword_options' , $shiword_opt );
		} else if ( !isset( $shiword_opt['version'] ) || $shiword_opt['version'] < $shiword_current_theme['Version'] ) {
			// check for unset values and set them to default value -> when updated to new version
			foreach ( $shiword_coa as $key => $val ) {
				if ( !isset( $shiword_opt[$key] ) ) $shiword_opt[$key] = $shiword_coa[$key]['default'];
			}
			$shiword_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'shiword_options' , $shiword_opt );
		}
}

// print a reminder message for set the options after the theme is installed
if ( !function_exists( 'shiword_setopt_admin_notice' ) ) {
	function shiword_setopt_admin_notice() {
		echo '<div class="updated"><p><strong>' . sprintf( __( "Shiword theme says: \"Don't forget to set <a href=\"%s\">my options</a> and the header image!\"", 'shiword' ), get_admin_url() . 'themes.php?page=tb_shiword_functions' ) . '</strong></p></div>';
	}
}

if ( current_user_can( 'manage_options' ) && $shiword_opt['version'] < $shiword_current_theme['Version'] ) {
	add_action( 'admin_notices', 'shiword_setopt_admin_notice' );
}

// check if in preview mode or not
$shiword_is_printpreview = false;
if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) { //print preview
	$shiword_is_printpreview = true;
}

// check if is "all category" page
$shiword_is_allcat_page = false;
if ( isset( $_GET['allcat'] ) && ( md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ) ) {
	$shiword_is_allcat_page = true;
}

if ( !function_exists( 'shiword_widgets_init' ) ) {
	function shiword_widgets_init() {
		global $shiword_opt;
		// Area 1, located at the top of the sidebar.
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

		// Area 2, located in the header. Empty by default.
		register_sidebar( array(
			'name' => __( 'Header Widget Area', 'shiword' ),
			'id' => 'header-widget-area',
			'description' => __( 'Tips: Don&apos;t drag too much widgets here. Use small &quot;graphical&quot; widgets (eg icons, buttons, the search form, etc.)', 'shiword' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="fwa_title">',
			'after_title' => '</div>',
		) );

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
	}
}

// Add stylesheets to page
if ( !function_exists( 'shiword_stylesheet' ) ) {
	function shiword_stylesheet(){
		global $shiword_version, $shiword_is_printpreview, $sw_is_mobile_browser;
		if ( $sw_is_mobile_browser ) {
			wp_enqueue_style( 'mobile-style', get_template_directory_uri() . '/mobile/style.css', false, $shiword_version, 'screen' );
			return;
		}
		//shows print preview / normal view
		if ( $shiword_is_printpreview ) { //print preview
			wp_enqueue_style( 'print-style-preview', get_template_directory_uri() . '/css/print.css', false, $shiword_version, 'screen' );
			wp_enqueue_style( 'general-style-preview', get_template_directory_uri() . '/css/print_preview.css', false, $shiword_version, 'screen' );
		} else { //normal view
			wp_enqueue_style( 'general-style', get_stylesheet_uri(), false, $shiword_version, 'screen' );
		}
		//print style
		wp_enqueue_style( 'print-style', get_template_directory_uri() . '/css/print.css', false, $shiword_version, 'print' );
	}
}

// add scripts
if ( !function_exists( 'shiword_scripts' ) ) {
	function shiword_scripts(){
		global $shiword_opt, $shiword_is_printpreview, $shiword_version, $sw_is_mobile_browser;
		if ( $sw_is_mobile_browser || is_admin() || $shiword_is_printpreview ) return;
		if ($shiword_opt['shiword_jsani'] == 1) {
			wp_enqueue_script( 'sw-animations', get_template_directory_uri().'/js/sw-animations.min.js',array('jquery'),$shiword_version, true ); //shiword js
			if ( $shiword_opt['shiword_sticky'] == 1 ) wp_enqueue_script( 'sw-sticky-slider', get_template_directory_uri().'/js/sw-sticky-slider.min.js',array('jquery'),$shiword_version , false );
		}
		wp_enqueue_script( 'sw-audio-player', get_template_directory_uri().'/resources/audio-player/audio-player-noswfobject.js',array('swfobject'),$shiword_version, false ); //audio player
	}
}

// show all categories list (redirect to allcat.php if allcat=y)
if ( !function_exists( 'shiword_allcat' ) ) {
	function shiword_allcat () {
		global $shiword_is_allcat_page;
		if ( $shiword_is_allcat_page ) {
			get_template_part( 'allcat' );
			exit;
		}
	}
}

// show mobile version
if ( !function_exists( 'shiword_mobile' ) ) {
	function shiword_mobile () {
		global $sw_is_mobile_browser;
		if ( $sw_is_mobile_browser ) {
			if ( is_singular() ) { 
				get_template_part( 'mobile/single' ); 
			} else {
				get_template_part( 'mobile/index' );
			}
			exit;
		}
	}
}

// setup for audio player
if ( !function_exists( 'shiword_setup_player' ) ) {
	function shiword_setup_player(){
		global $shiword_is_printpreview, $shiword_colors, $sw_is_mobile_browser;
		if ( $sw_is_mobile_browser || is_admin() || $shiword_is_printpreview ) return;
		?>
<script type="text/javascript">
	/* <![CDATA[ */
	swAudioPlayer.setup("<?php echo get_template_directory_uri().'/resources/audio-player/player.swf'; ?>", {  
		width: 415,
		loop: "yes",
		transparentpagebg: "yes",
		leftbg: "262626",
		lefticon: "aaaaaa",
		rightbg: "262626",
		righticon: "<?php echo str_replace("#", "", $shiword_colors['main3']); ?>",
		righticonhover: "<?php echo str_replace("#", "", $shiword_colors['main4']); ?>",
		animation: "no"
	});  
	/* ]]> */
</script>
		<?php
	}
}

// add "quote" link
if ( !function_exists( 'shiword_quote_scripts' ) ) {
	function shiword_quote_scripts(){
		global $shiword_opt, $sw_is_mobile_browser;
		if ( !is_admin() && ( $shiword_opt['shiword_quotethis'] == 1 ) && !$sw_is_mobile_browser && is_singular() ) {
		?>
			<script type="text/javascript">
				/* <![CDATA[ */
				if ( document.getElementById('reply-title') ) {
					sw_qdiv = document.createElement('small');
					sw_qdiv.innerHTML = ' - <a href="#" onclick="sw_quotethis(); return false" title="Add selected text as quote" ><?php _e( 'Quote', 'shiword' ); ?></a>';
					sw_replink = document.getElementById('reply-title');
					sw_replink.appendChild(sw_qdiv);
				}
				function sw_quotethis() {
					var posttext = '';
					if (window.getSelection){
						posttext = window.getSelection();
					}
					else if (document.getSelection){
						posttext = document.getSelection();
					}
					else if (document.selection){
						posttext = document.selection.createRange().text;
					}
					else {
						return true;
					}
					posttext = posttext.toString().replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
					if ( posttext.length !== 0 ) {
						document.getElementById("comment").value = document.getElementById("comment").value + '<blockquote>' + posttext + '</blockquote>';
					} else {
						alert("<?php _e( 'Nothing to quote. You must select some text...', 'shiword' ) ?>");
					}
				}
				/* ]]> */
			</script>
		<?php
		}
	}
}

// Get Recent Comments
if ( !function_exists( 'shiword_get_recentcomments' ) ) {
	function shiword_get_recentcomments() {
		$comments = get_comments( 'status=approve&number=10&type=comment' ); // valid type values (not documented) : 'pingback','trackback','comment'
		if ( $comments ) {
			foreach ( $comments as $comment ) {
				//if ( post_password_required( get_post( $comment->comment_post_ID ) ) ) { continue; } // uncomment to skip comments on protected posts. Hi Emma ;)
				$post = get_post( $comment->comment_post_ID );
				setup_postdata( $post );
				if ( $post->post_title == "" ) {
					$post_title_short = __( '(no title)', 'shiword' );
				} else {
					//shrink the post title if > 35 chars
					$post_title_short = mb_strimwidth( esc_html( $post->post_title ), 0, 35, '&hellip;' );
				}
				if ( post_password_required( $post ) ) {
					//hide comment author in protected posts
					$com_auth = __( 'someone','shiword' );
				} else {
					//shrink the comment author if > 20 chars
					$com_auth = mb_strimwidth( $comment->comment_author, 0, 20, '&hellip;' );
				}
			    echo '<li>'. $com_auth . ' ' . __( 'on', 'shiword' ) . ' <a href="' . get_permalink( $post->ID ) . '#comment-' . $comment->comment_ID . '">' . $post_title_short . '</a><div class="preview">';
			if ( post_password_required( $post ) ) {
				echo '[' . __( 'No preview: this is a comment of a protected post', 'shiword' ) . ']';
			} else {
				comment_excerpt( $comment->comment_ID );
			}
				echo '</div></li>';
			}
		} else {
			echo '<li>' . __( 'No comments yet.', 'shiword' ) . '</li>';
		}
	}
}

// Get Recent Entries
if ( !function_exists( 'shiword_get_recententries' ) ) {
	function shiword_get_recententries() {
		$lastposts = get_posts( 'numberposts=10' );
		foreach( $lastposts as $post ) {
			setup_postdata( $post );
			$post_title = esc_html( $post->post_title );
			if ( $post->post_title == "" ) {
				$post_title_short = __( '(no title)', 'shiword' );
			} else {
				//shrink the post title if > 35 chars
				$post_title_short = mb_strimwidth( esc_html( $post->post_title ), 0, 35, '&hellip;' );
			}
			//shrink the post author if > 20 chars
			$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );
			echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . sprintf( __( 'by %s', 'shiword' ), $post_auth ) . '<div class="preview">';
			if ( post_password_required( $post ) ) {
				echo '<img class="alignleft wp-post-image"  height="50" width="50" src="' . get_template_directory_uri() . '/images/thumb.png" alt="thumb" title="' . $post_title_short . '" />';
				echo '[' . __('No preview: this is a protected post', 'shiword' ) . ']';
			} else {
				echo shiword_get_the_thumb( $post->ID, 50, 50, 'alignleft' );
				//the_excerpt();
				echo has_excerpt($post->ID) ? $post->post_excerpt : wp_trim_excerpt('');
			}
			echo '</div></li>';
		}
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
				if ( $post->post_title == "" ) {
					$post_title_short = __( '(no title)', 'shiword' );
				} else {
					//shrink the post title if > 35 chars
					$post_title_short = mb_strimwidth( esc_html( $post->post_title ), 0, 35, '&hellip;' );
				}
				//shrink the post author if > 20 chars
				$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );
				echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . sprintf( __( 'by %s', 'shiword' ), $post_auth ) . '</li>';
			}
			echo '</ul></div></li>';
		}
	}
}

// Filter wp_nav_menu() to add additional links and other output
if ( !function_exists( 'shiword_new_nav_menu_items' ) ) {
	function shiword_new_nav_menu_items($items) {
		$floatfixer = '<li class="menu-float-fixer"></li>';
		$items = $items .$floatfixer;
		return $items;
	}
}

// Pages Menu
if ( !function_exists( 'shiword_pages_menu' ) ) {
	function shiword_pages_menu() {
		echo '<div id="sw-pri-menu" class="sw-menu"><ul id="mainmenu">';
		wp_list_pages( 'sort_column=menu_order&title_li=' ); // menu-order sorted
		echo '</ul></div>';
	}
}

// Pages Menu (mobile)
if ( !function_exists( 'shiword_pages_menu_mobile' ) ) {
	function shiword_pages_menu_mobile() {
		echo '<div id="sw-pri-menu" class="sw-menu "><ul id="mainmenu" class="sw-group">';
		wp_list_pages( 'sort_column=menu_order&title_li=&depth=1' ); // menu-order sorted
		echo '</ul><div class="fixfloat"></div></div>';
	}
}

// pages navigation links
if ( !function_exists( 'shiword_page_navi' ) ) {
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
}

// page hierarchy
if ( !function_exists( 'shiword_multipages' ) ) {
	function shiword_multipages(){
		global $post;
		$args = array(
			'post_type' => 'page',
			'post_parent' => $post->ID,
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'numberposts' => 0
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
}

// print extra info for posts/pages
if ( !function_exists( 'shiword_extrainfo' ) ) {
	function shiword_extrainfo( $auth, $date, $comms, $tags, $cats, $hiera = false ) {
		global $shiword_opt;
		$r_pos = 10;
		if ( $shiword_opt['shiword_xinfos_static'] == 0 ) {
		?>
		<div class="meta_container">
			<div class="meta top_meta ani_meta">
			<?php
			if ( $auth && ( $shiword_opt['shiword_byauth'] == 1 ) ) { ?>
				<?php $post_auth = ( $auth === true ) ? '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( 'View all posts by %s', esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' : $auth; ?>
				<div class="metafield_trigger" style="left: 10px;"><?php printf( __( 'by %s', 'shiword' ), $post_auth ); ?></div>
			<?php
			}
			if ( $cats && ( $shiword_opt['shiword_xinfos_cat'] == 1 ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_cat" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php echo __( 'Categories', 'shiword' ) . ':'; ?>
						<?php the_category( ', ' ); ?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			if ( $tags && ( $shiword_opt['shiword_xinfos_tag'] == 1 ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_tag" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php _e( 'Tags:', 'shiword' ); ?>
						<?php if ( !get_the_tags() ) { _e( 'No Tags', 'shiword' ); } else { the_tags('', ', ', ''); } ?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			if ( $comms && ( $shiword_opt['shiword_xinfos_comm'] == 1 ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_comm" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php _e( 'Comments', 'shiword' ); ?>:
						<?php comments_popup_link( __( 'No Comments', 'shiword' ), __( '1 Comment', 'shiword', 'shiword' ), __( '% Comments', 'shiword' ) ); // number of comments?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			if ( $date && ( $shiword_opt['shiword_xinfos_date'] == 1 ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_date" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php
						printf( __( 'Published on: <b>%1$s</b>', 'shiword' ), '' );
						the_time( get_option( 'date_format' ) );
						?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			if ( $hiera ) {
			?>
			<?php if ( shiword_multipages() ) { $r_pos = $r_pos + 30; } ?>
			<?php
			}
			?>
				<div class="metafield_trigger edit_link" style="right: <?php echo $r_pos; ?>px;"><?php edit_post_link( __( 'Edit', 'shiword' ),'' ); ?></div>
			</div>
		</div>
		<?php
		} else { ?>
			<div class="meta">
				<?php if ( $auth && ( $shiword_opt['shiword_byauth'] == 1 ) ) { printf( __( 'by %s', 'shiword' ), '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( 'View all posts by %s', esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' ); echo '<br />'; }; ?>
				<?php if ( $date && ( $shiword_opt['shiword_xinfos_date'] == 1 ) ) { printf( __( 'Published on: %1$s', 'shiword' ), get_the_time( get_option( 'date_format' ) ) ) ; echo '<br />'; }?>
				<?php if ( $comms && ( $shiword_opt['shiword_xinfos_comm'] == 1 ) ) { echo __( 'Comments', 'shiword' ) . ': '; comments_popup_link( __( 'No Comments', 'shiword' ), __( '1 Comment', 'shiword', 'shiword' ), __( '% Comments', 'shiword' ) ); echo '<br />'; } ?>
				<?php if ( $tags && ( $shiword_opt['shiword_xinfos_tag'] == 1 ) ) { echo __( 'Tags:', 'shiword' ) . ' '; if ( !get_the_tags() ) { _e( 'No Tags', 'shiword' ); } else { the_tags('', ', ', ''); }; echo '<br />';  } ?>
				<?php if ( $cats && ( $shiword_opt['shiword_xinfos_cat'] == 1 ) ) { echo __( 'Categories', 'shiword' ) . ':'; the_category( ', ' ); echo '<br />'; } ?>
				<?php edit_post_link( __( 'Edit', 'shiword' ) ); ?>
			</div>
		<?php
		}
	}
}

//add a fix for embed videos overlaing quickbar
if ( !function_exists( 'shiword_content_replace' ) ) {
	function shiword_content_replace( $content ) {
		$content = str_replace( '<param name="allowscriptaccess" value="always">', '<param name="allowscriptaccess" value="always"><param name="wmode" value="transparent">', $content );
		$content = str_replace( '<embed ', '<embed wmode="transparent" ', $content );
		return $content;
	}
}

// Get first image of a post
if ( !function_exists( 'shiword_get_first_image' ) ) {
	function shiword_get_first_image() {
		global $post, $posts;
		$first_info = '';
		//search the images in post content
		preg_match_all( '/<img[^>]+>/i',$post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_info['img'] = $result[0][0];
			$first_img = $result [0][0];
			//get the title (if any)
			preg_match_all( '/(title)=("[^"]*")/i',$first_img, $img_title );
			if ( isset( $img_title[2][0] ) ){
				$first_info['title'] = str_replace( '"','',$img_title[2][0] );
			}
			//get the path
			preg_match_all( '/(src)=("[^"]*")/i',$first_img, $img_src );
			if ( isset( $img_src[2][0] ) ){
				$first_info['src'] = str_replace( '"','',$img_src[2][0] );
			}
			return $first_info;
		}
	}
}

// Get first link of a post
if ( !function_exists( 'shiword_get_first_link' ) ) {
	function shiword_get_first_link() {
		global $post, $posts;
		$first_info = '';
		//search the link in post content
		preg_match_all( '/<a [^>]+>/i',$post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_info['anchor'] = $result[0][0];
			$first_link = $result [0][0];
			//get the title (if any)
			preg_match_all( '/(title)=("[^"]*")/i',$first_link, $link_title );
			if ( isset( $link_title[2][0] ) ){
				$first_info['title'] = str_replace( '"','',$link_title[2][0] );
			}
			//get the path
			preg_match_all( '/(href)=("[^"]*")/i',$first_link, $link_href );
			if ( isset($link_href[2][0] ) ){
				$first_info['href'] = str_replace( '"','',$link_href[2][0] );
			}
			return $first_info;
		}
	}
}

// Get first blockquote words
if ( !function_exists( 'shiword_get_blockquote' ) ) {
	function shiword_get_blockquote() {
		global $post, $posts;
		$first_quote = array( 'quote' => '', 'cite' => '' );
		//search the blockquote in post content
		preg_match_all( '/<blockquote>([\w\W]*?)<\/blockquote>/',$post->post_content, $blockquote );
		//grab the first one
		if ( isset( $blockquote[0][0] ) ){
			$first_quote['quote'] = strip_tags( $blockquote[0][0] );
			$words = explode( " ", $first_quote['quote'], 6 );
			if ( count( $words ) == 6 ) $words[5] = '...';
			$first_quote['quote'] = implode( ' ', $words );
			preg_match_all( '/<cite>([\w\W]*?)<\/cite>/',$blockquote[0][0], $cite );
			$first_quote['cite'] = ( isset( $cite[1][0] ) ) ? $cite[1][0] : '';
			return $first_quote;
		}
	}
}

// search for linked mp3's and add an audio player
if ( !function_exists( 'shiword_add_audio_player' ) ) {
	function shiword_add_audio_player( $text = '' ) {
		global $post;
		$pattern = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.mp3))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";
		if ( $text == '')
			preg_match_all( $pattern, $post->post_content, $result );
		else
			preg_match_all( $pattern, $text, $result );
		$data = $result[3];
		if ( $data ) { ?>
			<div class="swf-audio-player"><div id="swf-audio-player-<?php the_ID(); ?>"></div><small>Audio clip: Adobe Flash Player (version 9 or above) is required to play this audio clip. Download the latest version <a href="http://get.adobe.com/flashplayer/" title="Download Adobe Flash Player">here</a>. You also need to have JavaScript enabled in your browser.</small></div>
			<script type="text/javascript">  
				/* <![CDATA[ */
				swAudioPlayer.embed("swf-audio-player-<?php the_ID(); ?>", {  
					soundFile: "<?php echo implode( "," , $data ); ?>"
				});  
				/* ]]> */
			</script>  			
			<?php return true;
		}
		return false;
	}
}

// get the post thumbnail or (if not set) the format related icon
if ( !function_exists( 'shiword_get_the_thumb' ) ) {
	function shiword_get_the_thumb( $id, $size_w, $size_h, $class, $default = '' ) {
		if ( has_post_thumbnail( $id ) ) {
			return get_the_post_thumbnail( $id, array( $size_w,$size_h ), array( 'class' => $class ) );
		} else {
			if ( function_exists( 'get_post_format' ) && get_post_format( $id ) ) {
				$format = get_post_format( $id );
			} else {
				$format = 'thumb';
			}
			return '<img class="' . $class . ' wp-post-image" width="' . $size_w . '" height="' . $size_h . '" alt="thumb" src="' . get_template_directory_uri() . '/images/thumbs/' . $format . '.png" />';
		}
	}
}

// create custom theme settings menu
if ( !function_exists( 'shiword_create_menu' ) ) {
	function shiword_create_menu() {
		//create new top-level menu - Theme Options
		$topage = add_theme_page( __( 'Theme Options', 'shiword' ), __( 'Theme Options', 'shiword' ), 'edit_theme_options', 'tb_shiword_functions', 'shiword_edit_options' );
		//create new top-level menu - Slideshow
		$slidepage = add_theme_page( __( 'Slideshow', 'shiword' ), __( 'Slideshow', 'shiword' ), 'edit_theme_options', 'tb_shiword_slideshow', 'shiword_edit_slideshow' );
		//call register settings function
		add_action( 'admin_init', 'shiword_register_settings' );
		//call custom stylesheet function
		add_action( 'admin_print_styles-widgets.php', 'shiword_widgets_style' );
		add_action( 'admin_print_styles-appearance_page_custom-background', 'shiword_custom_background_style' );
		add_action( 'admin_print_styles-' . $topage, 'shiword_theme_options_style' );
		add_action( 'admin_print_scripts-' . $topage, 'shiword_theme_options_script' );
		add_action( 'admin_print_styles-' . $slidepage, 'shiword_slide_options_style' );
		add_action( 'admin_print_scripts-' . $slidepage, 'shiword_slide_options_script' );
	}
}

if ( !function_exists( 'shiword_register_settings' ) ) {
	function shiword_register_settings() {
		//register general settings
		register_setting( 'shiw_settings_group', 'shiword_options', 'shiword_sanitize_options' );
		//register slideshow settings
		register_setting( 'shiw_slideshow_group', 'shiword_slideshow', 'shiword_sanitize_slideshow'  );
		//register colors settings
		register_setting( 'shiw_colors_group', 'shiword_colors'  );
	}
}

if ( !function_exists( 'shiword_custom_background_style' ) ) {
	function shiword_custom_background_style() {
		//add custom stylesheet
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/custom-background.css" />';
	}
}

if ( !function_exists( 'shiword_widgets_style' ) ) {
	function shiword_widgets_style() {
		//add custom stylesheet
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/widgets.css" />' . "\n";
	}
}

if ( !function_exists( 'shiword_theme_options_style' ) ) {
	function shiword_theme_options_style() {
		//add custom stylesheet
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/theme-options.css" />' . "\n";
	}
}

if ( !function_exists( 'shiword_slide_options_style' ) ) {
	function shiword_slide_options_style() {
		//add custom stylesheet
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/slide-options.css" />' . "\n";
	}
}

if ( !function_exists( 'shiword_slide_options_script' ) ) {
	function shiword_slide_options_script() {
		global $shiword_version;
		wp_enqueue_script( 'sw_otp_script', get_template_directory_uri().'/js/sw-otp-script.dev.js',array('jquery'),$shiword_version, true ); //shiword js
	}
}

if ( !function_exists( 'shiword_theme_options_script' ) ) {
	function shiword_theme_options_script() {
		global $shiword_version;
		wp_enqueue_script( 'sw_otp_tabs_script', get_template_directory_uri().'/js/sw-otp-tabs-script.dev.js',array('jquery'),$shiword_version, true ); //shiword js
	}
}

// sanitize options value
if ( !function_exists( 'shiword_sanitize_options' ) ) {
	function shiword_sanitize_options( $input ){
		global $shiword_coa, $shiword_current_theme;
		// check for updated values and return 0 for disabled ones <- index notice prevention
		foreach ( $shiword_coa as $key => $val ) {

			if( $shiword_coa[$key]['type'] == 'chk' ) {
				if( !isset( $input[$key] ) ) {
					$input[$key] = 0;
				} else {
					$input[$key] = ( $input[$key] == 1 ? 1 : 0 );
				}
			} elseif( $shiword_coa[$key]['type'] == 'sel' ) {
				if ( !in_array( $input[$key], $shiword_coa[$key]['options'] ) ) $input[$key] = $shiword_coa[$key]['default'];
			}
		}
		// check for required options
		foreach ( $shiword_coa as $key => $val ) {
			if ( $shiword_coa[$key]['req'] != '' ) { if ( $input[$shiword_coa[$key]['req']] == 0 ) $input[$key] = 0; }
		}
		$input['version'] = $shiword_current_theme['Version']; // keep version number
		return $input;
	}
}

// the option page
if ( !function_exists( 'shiword_edit_options' ) ) {
	function shiword_edit_options() {
	  if ( !current_user_can( 'edit_theme_options' ) ) {
	    wp_die( __( 'You do not have sufficient permissions to access this page.', 'shiword' ) );
	  }
		global $shiword_coa, $shiword_opt, $shiword_current_theme;
		
		// update version value when admin visit options page
		if ( $shiword_opt['version'] < $shiword_current_theme['Version'] ) {
			$shiword_opt['version'] = $shiword_current_theme['Version'];
			update_option( 'shiword_options' , $shiword_opt );
		}
	?>
		<div class="wrap">
			<div class="icon32" id="icon-themes"><br></div>
			<h2><?php echo get_current_theme() . ' - ' . __( 'Theme Options', 'shiword' ); ?></h2>
			<?php
				// return options save message
				if ( isset( $_REQUEST['settings-updated'] ) ) {
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Options saved.', 'shiword' ) . '</strong></p></div>';
				}
			?>
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
						
							<ul id="sw-tabselector" class="hide-if-no-js">
								<li class="sw-selgroup-other"><a href="#" onClick="shiwordSwitchTab.set('other'); return false;"><?php _e( 'other' , 'shiword' ); ?></a></li>
								<li class="sw-selgroup-slideshow"><a href="#" onClick="shiwordSwitchTab.set('slideshow'); return false;"><?php _e( 'slideshow' , 'shiword' ); ?></a></li>
								<li class="sw-selgroup-sidebar"><a href="#" onClick="shiwordSwitchTab.set('sidebar'); return false;"><?php _e( 'sidebar' , 'shiword' ); ?></a></li>
								<li class="sw-selgroup-postformats"><a href="#" onClick="shiwordSwitchTab.set('postformats'); return false;"><?php _e( 'post formats' , 'shiword' ); ?></a></li>
								<li class="sw-selgroup-content"><a href="#" onClick="shiwordSwitchTab.set('content'); return false;"><?php _e( 'content' , 'shiword' ); ?></a></li>
								<li class="sw-selgroup-quickbar"><a href="#" onClick="shiwordSwitchTab.set('quickbar'); return false;"><?php _e( 'quickbar' , 'shiword' ); ?></a></li>
							</ul>
							<table style="border-collapse: collapse; width: 100%;background-color:#fff;" id="sw-opt-table">
								<tr>
									<th><?php _e( 'name' , 'shiword' ); ?></th>
									<th><?php _e( 'status' , 'shiword' ); ?></th>
									<th><?php _e( 'description' , 'shiword' ); ?></th>
									<th><?php _e( 'require' , 'shiword' ); ?></th>
								</tr>
							<?php foreach ($shiword_coa as $key => $val) { ?>
								<?php if ( $shiword_coa[$key]['type'] == 'chk' ) { ?>
								<tr class="sw-tab-opt sw-tabgroup-<?php echo $shiword_coa[$key]['group']; ?>">
									<td class="sh-opt-descr"><?php echo $shiword_coa[$key]['description']; ?></td>
									<td class="sh-opt-chk">
										<input name="shiword_options[<?php echo $key; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $shiword_opt[$key] ); ?> />
									</td>
									<td class="sh-opt-nfo"><?php echo $shiword_coa[$key]['info']; ?></td>
									<td class="sh-opt-req"><?php if ( $shiword_coa[$key]['req'] != '' ) echo $shiword_coa[$shiword_coa[$key]['req']]['description']; ?></td>
								</tr>
								<?php } elseif ( $shiword_coa[$key]['type'] == 'sel' ) { ?>
								<tr class="sw-tab-opt sw-tabgroup-<?php echo $shiword_coa[$key]['group']; ?>">
									<td class="sh-opt-descr"><?php echo $shiword_coa[$key]['description']; ?></td>
									<td class="sh-opt-chk">
										<select name="shiword_options[<?php echo $key; ?>]">
										<?php foreach($shiword_coa[$key]['options'] as $option) { ?>
											<option value="<?php echo $option; ?>" <?php selected( $shiword_opt[$key], $option ); ?>><?php echo $option; ?></option>
										<?php } ?>
										</select>
									</td>
									<td class="sh-opt-nfo"><?php echo $shiword_coa[$key]['info']; ?></td>
									<td class="sh-opt-req"><?php if ( $shiword_coa[$key]['req'] != '' ) echo $shiword_coa[$shiword_coa[$key]['req']]['description']; ?></td>
								</tr>
								<?php }	?>
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
}

if ( !function_exists( 'shiword_sanitize_slideshow' ) ) {
	function shiword_sanitize_slideshow( $input ){
		if ( !empty($input) ) {
			//check for numeric value
			foreach ( $input as $key => $val ) {
				if ( is_numeric( $val ) ) {
					$input[$key] = $val;
				} else {
					 unset( $input[$key] );
				}
			}
		}
		return $input;
	}
}

// the slideshow admin panel - here you can select posts to be included in slideshow
if ( !function_exists( 'shiword_edit_slideshow' ) ) {
	function shiword_edit_slideshow() {
		$shiword_options = get_option( 'shiword_slideshow' );
		//return options save message
		if ( isset( $_REQUEST['settings-updated'] ) ) {
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
										if ( !isset( $shiword_options[$post->ID] ) ) $shiword_options[$post->ID] = 0;
										?>
										<tr class="sw_post_row">
											<th class="check-column" scope="row">
												<input name="shiword_slideshow[<?php echo $post->ID; ?>]" value="<?php echo $post->ID; ?>" type="checkbox" class="" <?php checked( $post->ID , $shiword_options[$post->ID] ); ?> />
											</th>
											<td class="post-title column-title">
												<?php echo shiword_get_the_thumb( $post->ID, 40, 40, 'slide-thumb' ); ?>
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
										if ( !isset( $shiword_options[$page->ID] ) ) $shiword_options[$page->ID] = 0;
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
}

// display a slideshow for the selected posts
if ( !function_exists( 'shiword_sticky_slider' ) ) {
	function shiword_sticky_slider() {
		global $post;
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
								<?php echo shiword_get_the_thumb( $post->ID, 120, 120, 'alignleft' ); ?>
							</a>
							<div style="padding-left: 130px;">
								<h2 class="storytitle"><a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a></h2> <?php printf( __( 'by %s', 'shiword' ), get_the_author() ); ?>
								<div style="font-size:12px">
									<?php the_excerpt(); ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php add_action( 'wp_footer', 'shiword_init_sticky_slider' ); //include the initialize code in footer
	}
}

if ( !function_exists( 'shiword_init_sticky_slider' ) ) {
	function shiword_init_sticky_slider() { ?>
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
}

if ( !function_exists( 'shiword_setup' ) ) {
	function shiword_setup() {

		global $shiword_opt;
		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );

		// This theme uses post formats
		if ( isset( $shiword_opt['shiword_postformats'] ) && ( $shiword_opt['shiword_postformats'] == 1 ) ) add_theme_support( 'post-formats', array( 'aside', 'gallery', 'audio', 'quote', 'image', 'video', 'link', 'status' ) );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Add the editor style
		if ( isset( $shiword_opt['shiword_editor_style'] ) && ( $shiword_opt['shiword_editor_style'] == 1 ) ) add_editor_style( 'css/editor-style.css' );

		// Theme uses wp_nav_menu() in two locations
		register_nav_menus( array( 'primary' => __( 'Main Navigation Menu', 'shiword' ), 'secondary' => __( 'Secondary Navigation Menu', 'shiword' ) ) );

		// This theme allows users to set the device appearance
		shiword_add_custom_device_image();

		// Your changeable header business starts here
		define( 'HEADER_TEXTCOLOR', '404040' );
		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		define( 'HEADER_IMAGE', '%s/images/headers/green.jpg' );

		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to shiword_header_image_width and shiword_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH', 850 );

		$head_h = ( isset( $shiword_opt['shiword_head_h'] ) ? str_replace( 'px', '', $shiword_opt['shiword_head_h']) : 100 );

		define( 'HEADER_IMAGE_HEIGHT', $head_h );

		// Don't support text inside the header image.
		define( 'NO_HEADER_TEXT', true );

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See shiword_admin_header_style(), below.
		add_custom_image_header( 'shiword_header_style', 'shiword_admin_header_style' );

		// Add a way for the custom background to be styled in the admin panel that controls
		add_custom_background( 'shiword_custom_bg' , '' , '' );

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
			),
			'butterflies' => array(
				'url' => '%s/images/headers/butterflies.gif',
				'thumbnail_url' => '%s/images/headers/butterflies_thumbnail.jpg',
				'description' => 'butterflies'
			)
		) );
		shiword_register_default_device_images( array(
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
			),
			'vector' => array(
				'url' => '%s/images/device/vector.png',
				'description' => 'vector'
			),
			'ice' => array(
				'url' => '%s/images/device/ice.png',
				'description' => 'ice'
			),
			'metal' => array(
				'url' => '%s/images/device/metal.png',
				'description' => 'metal'
			),
			'stripe' => array(
				'url' => '%s/images/device/stripe.png',
				'description' => 'stripe'
			),
			'flower' => array(
				'url' => '%s/images/device/flower.png',
				'description' => 'flower'
			),
			'wood' => array(
				'url' => '%s/images/device/wood.jpg',
				'description' => 'wood'
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

// convert hex color value to rgba
if ( !function_exists( 'shiword_hex2rgba' ) ) {
	function shiword_hex2rgba($hex,$alpha) {
		$color = str_replace('#','',$hex);
		if ( $color == 'transparent' ) {
			return 'transparent';
		} else {
			$rgba = 'rgba(' . hexdec(substr($color,0,2)) . ',' . hexdec(substr($color,2,2)) . ',' . hexdec(substr($color,4,2)) . ',' . round(intval($alpha) / 100, 3) . ')';
			return $rgba;
		}
	}
}

// custom header image style - gets included in the site header
if ( !function_exists( 'shiword_header_style' ) ) {
	function shiword_header_style() {
		global $shiword_colors, $sw_is_mobile_browser;
		if ( $sw_is_mobile_browser ) return;
		$device_rgba = shiword_hex2rgba( $shiword_colors['device_color'], $shiword_colors['device_opacity']);
	    ?>
	<style type="text/css">
		#headerimg {
			background: transparent url('<?php esc_url ( header_image() ); ?>') right bottom repeat-y;
			<?php //if ( get_theme_mod( 'header_image' , HEADER_IMAGE ) == '' ) echo 'display: none;'; ?>
			min-height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
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
		a {
			color : <?php echo $shiword_colors['main3']; ?>;
		}
		a:hover {
			color : <?php echo $shiword_colors['main4']; ?>;
		}
		.sw-menu,
		#mainmenu > li:hover,
		#mainmenu > li.page_item > ul.children,
		#mainmenu > li.menu-item > ul.sub-menu,
		.minibutton .nb_tooltip,
		.menuback {
			background-color: <?php echo $shiword_colors['menu1']; ?>;
			border-color: <?php echo $shiword_colors['menu2']; ?>;
			color: <?php echo $shiword_colors['menu3']; ?>;
		}
		.sw-menu a,
		.sw-menu > li.page_item > ul.children a,
		.sw-menu > li.menu-item > ul.sub-menu a,
		.menuback a {
			color: <?php echo $shiword_colors['menu4']; ?>;
		}
		.sw-menu a:hover,
		.sw-menu li:hover .hiraquo,
		.sw-menu > li.page_item > ul.children a:hover,
		.sw-menu > li.menu-item > ul.sub-menu a:hover,
		.minibutton .nb_tooltip a:hover,
		.menuback a:hover,
		.sw-menu .current-menu-item > a,
		.sw-menu .current_page_item > a,
		li.current_page_ancestor .hiraquo,
		.sw-menu .current-cat > a,
		.sw-menu a:hover,
		.sw-menu .current-menu-item a:hover,
		.sw-menu .current_page_item a:hover,
		.sw-menu .current-cat a:hover {
			color: <?php echo $shiword_colors['menu5']; ?>;
		}
		.menu_sx > ul {
			border-color: <?php echo $shiword_colors['menu6']; ?>;
		}
		.menuback .mentit {
			color: <?php echo $shiword_colors['menu3']; ?>;
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
		.social-like li a img {
			border: 1px solid <?php echo $shiword_colors['main3']; ?>;

		}
		.social-like li a img:hover {
			border: 1px solid <?php echo $shiword_colors['main4']; ?>;
		}
		.h2-ext-link {
			background-color: <?php echo $shiword_colors['main3']; ?>;
		}
		.h2-ext-link:hover {
			background-color: <?php echo $shiword_colors['main4']; ?>;
		}
		.minib_img {
			background-image: url('<?php echo get_template_directory_uri(); ?>/images/minibuttons-<?php echo $shiword_colors['device_button_style']; ?>.png');
		}
		.menuitem_img {
			background-image: url('<?php echo get_template_directory_uri(); ?>/images/qbar-<?php echo $shiword_colors['device_button_style']; ?>.png');
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
		.storycontent img.size-full {
			width:auto;
		}
	</style>
	<![endif]-->
	  <?php
	}
}

// custom background style - gets included in the site header
if ( !function_exists( 'shiword_custom_bg' ) ) {
	function shiword_custom_bg() {
		$color = get_background_color();
		if ( ! $color ) return;
		?>
		<style type="text/css"> 
			body { background-color: #<?php echo $color; ?>; }
			#head_cont { background: #<?php echo $color; ?>; }
		</style>
		<?php
	}
}

//get the theme color values. uses default values if options are empty or unset
function shiword_get_colors() {

	/* Holds default colors. */
	$default_device_colors = shiword_get_default_colors('all');

	$shiword_colors = get_option( 'shiword_colors' );
	foreach ( $default_device_colors as $key => $val ) {
		if ( ( !isset( $shiword_colors[$key] ) ) || empty( $shiword_colors[$key] ) ) {
			$shiword_colors[$key] = $default_device_colors[$key];
		}
	}
	return $shiword_colors;
}

//set the excerpt lenght
if ( !function_exists( 'shiword_excerpt_length' ) ) {
	function shiword_excerpt_length( $length ) {
		return 50;
	}
}

//styles the login page
if ( !function_exists( 'shiword_custom_login_css' ) ) {
	function shiword_custom_login_css() {
	    echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/login.css" />' . "\n";
	}
}

//Add new contact methods to author panel
if ( !function_exists( 'shiword_new_contactmethods' ) ) {
	function shiword_new_contactmethods( $contactmethods ) {
		//add Twitter
		$contactmethods['twitter'] = 'Twitter';
		//add Facebook
		$contactmethods['facebook'] = 'Facebook';

		return $contactmethods;
	}
}

//add a default gravatar
if ( !function_exists( 'shiword_addgravatar' ) ) {
	function shiword_addgravatar( $avatar_defaults ) {
	  $myavatar = get_template_directory_uri() . '/images/user.png';
	  $avatar_defaults[$myavatar] = __( 'shiword Default Gravatar', 'shiword' );

	  return $avatar_defaults;
	}
}

// add 'quoted on' before trackback/pingback comments link
if ( !function_exists( 'shiword_add_quoted_on' ) ) {
	function shiword_add_quoted_on( $return ) {
		global $comment;
		$text = '';
		if ( get_comment_type() != 'comment' ) {
			$text = '<span style="font-weight: normal;">' . __( 'quoted on', 'shiword' ) . ' </span>';
		}
		return $text . $return;
	}
}

// the real comment count
if ( !function_exists( 'shiword_comment_count' ) ) {
	function shiword_comment_count( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
		} else {
			return $count;
		}
	} 
}

// custom image caption
if ( !function_exists( 'shiword_img_caption_shortcode' ) ) {
	function shiword_img_caption_shortcode( $deprecated, $attr, $content = null) {

		extract(shortcode_atts(array(
			'id'	=> '',
			'align'	=> 'alignnone',
			'width'	=> '',
			'caption' => ''
		), $attr));

		if ( 1 > (int) $width || empty($caption) )
			return $content;

		if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

		return '<div ' . $id . 'class="img-caption ' . esc_attr($align) . '" style="width: ' . $width . 'px"><div class="img-caption-inside">'
		. do_shortcode( $content ) . '<div class="img-caption-text">' . $caption . '</div></div></div>';
	}
}

// custom gallery shortcode function
function shiword_gallery_shortcode($attr) {
	global $post, $wp_locale;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = '';
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

		$output .= '
			<div class="gallery-item img-caption" style="width: ' . get_option('thumbnail_size_w') . 'px;">';
		$output .= "
				<div class='img-caption-inside'>
					$link";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<div class='img-caption-text'>
				" . wptexturize($attachment->post_excerpt) . "
				</div>";
		}
		$output .= "
				</div>
			</div>";
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n";

	return $output;
}


// Register a selection of default images to be displayed as device backgrounds by the custom device color admin UI. based on WP theme.php -> register_default_headers()
if ( !function_exists( 'shiword_register_default_device_images' ) ) {
	function shiword_register_default_device_images( $headers ) {
		global $sw_default_device_images;

		$sw_default_device_images = array_merge( (array) $sw_default_device_images , (array) $headers );
	}
}

// Add callbacks for device color display. based on WP theme.php -> add_custom_image_header()
if ( !function_exists( 'shiword_add_custom_device_image' ) ) {
	function shiword_add_custom_device_image() {
		if ( ! is_admin() )
			return;
		require_once( 'custom-device-color.php' );
		$GLOBALS['custom_device_color'] =& new Custom_device_color();
		add_action( 'admin_menu' , array(&$GLOBALS['custom_device_color'] , 'init' ));
	}
}

// display a simple login form in quickbar
if ( !function_exists( 'shiword_mini_login' ) ) {
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
			<?php if ( isset( $shiword_opt['shiword_qbar_minilogin'] ) && ( $shiword_opt['shiword_qbar_minilogin'] == 1 ) && ( !class_exists("siCaptcha") ) ) { ?>
				<div class="cat_preview">
					<div class="mentit"><?php _e( 'Log in', 'shiword' ); ?></div>
					<div id="sw_minilogin" class="solid_ul">
						<?php wp_login_form($args); ?>
						<a id="closeminilogin" href="#" style="display: none; margin-left:10px;"><?php _e( 'Close', 'shiword' ); ?></a>
					</div>
				</div>
			<?php } ?>
		</li>

		<?php
	}
}

//non multibyte fix
if ( !function_exists( 'mb_strimwidth' ) ) {
	function mb_strimwidth( $string, $start, $length, $wrap = '&hellip;' ) {
		if ( strlen( $string ) > $length ) {
			$ret_string = substr( $string, $start, $length ) . $wrap;
		} else {
			$ret_string = $string;
		}
		return $ret_string;
	}
}

// load the custom widgets module
get_template_part('widgets');

?>

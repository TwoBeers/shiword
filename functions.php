<?php
/**** begin theme hooks ****/
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
// Add the "quote" link
add_action( 'wp_footer', 'shiword_quote_scripts' );
// Add the "quote" link
add_action( 'wp_footer', 'shiword_detect_js' );
// load "like" badges js
add_action( 'wp_footer', 'shiword_I_like_it_js' );
// setup for audio player
add_action( 'wp_head', 'shiword_setup_player' );
// stylesheet for ie6
add_action( 'wp_head', 'shiword_ie6_style' );
// deregister styles
add_action( 'wp_print_styles', 'shiword_deregister_styles', 100 );
// add links to admin bar
add_action('admin_bar_menu', 'shiword_admin_bar_plus', 999);  
// Custom filters
add_filter( 'embed_oembed_html', 'shiword_wmode_transparent', 10, 3);
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

//load options
$shiword_opt = get_option( 'shiword_options' );

// load modules (accordingly to http://justintadlock.com/archives/2010/11/17/how-to-load-files-within-wordpress-themes)
require_once('lib/options.php'); // load theme default options
require_once('lib/admin.php'); // load admin stuff
require_once('lib/hooks.php'); // load custom hooks
require_once('quickbar.php'); // load quickbar functions
if ( $shiword_opt['shiword_custom_widgets'] == 1 ) require_once('lib/widgets.php'); // load custom widgets module



/**** begin theme checks ****/
$shiword_is_mobile_browser = shiword_mobile_device_detect(); // check if is mobile browser
function shiword_mobile_device_detect() {
	global $shiword_opt;
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    if ( ( !isset( $shiword_opt['shiword_mobile_css'] ) || ( $shiword_opt['shiword_mobile_css'] == 1) ) && preg_match( '/(ipad|ipod|iphone|android|opera mini|blackberry|palm|symbian|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i' , $user_agent ) ) { // there were other words for mobile detecting but this is enought ;-)
		return true;
	} else {
		return false;
	}
}

$shiword_is_printpreview = false; // check if in preview mode or not
if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) { //print preview
	$shiword_is_printpreview = true;
}

$shiword_is_allcat_page = false; // check if is "all category" page
if ( isset( $_GET['allcat'] ) && ( md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ) ) {
	$shiword_is_allcat_page = true;
}
/**** end theme checks ****/


// Set the content width based on the theme's design
if ( ! isset( $content_width ) ) {
	if ( ! $shiword_is_mobile_browser ) {
		$content_width = $shiword_opt['shiword_frame_width'] - 290;
	} else {
		$content_width = 300;
	}
}

// get theme version
if ( get_theme( 'Shiword' ) ) {
	$shiword_current_theme = get_theme( 'Shiword' );
	$shiword_version = $shiword_current_theme['Version'];
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
		// Area 6, located just after post/page content. Empty by default.
		register_sidebar( array(
			'name' => __( 'Single Widget Area', 'shiword' ),
			'id' => 'single-widget-area',
			'description' => __( 'Located after the post/page content, it is the ideal place for your widgets related to individual entries', 'shiword' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );
		// Area 7, located in page 404.
		register_sidebar( array(
			'name' => __( 'Page 404', 'shiword' ),
			'id' => '404-widgets-area',
			'description' => __( 'Enrich the page 404 with some useful widgets', 'shiword' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );
	}
}

// Add stylesheets to page
if ( !function_exists( 'shiword_stylesheet' ) ) {
	function shiword_stylesheet(){
		global $shiword_version, $shiword_is_printpreview, $shiword_is_mobile_browser, $shiword_opt;
		if ( is_admin() ) return;
		if ( $shiword_is_mobile_browser ) {
			wp_enqueue_style( 'sw-mobile-style', get_template_directory_uri() . '/mobile/mobile-style.css', false, $shiword_version, 'screen' );
			return;
		}
		//shows print preview / normal view
		if ( $shiword_is_printpreview ) { //print preview
			wp_enqueue_style( 'sw-print-style-preview', get_template_directory_uri() . '/css/print.css', false, $shiword_version, 'screen' );
			wp_enqueue_style( 'sw-general-style-preview', get_template_directory_uri() . '/css/print_preview.css', false, $shiword_version, 'screen' );
		} else { //normal view
			//thickbox style
			if ( ( $shiword_opt['shiword_thickbox'] == 1 ) ) wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'sw-general-style', get_stylesheet_uri(), false, $shiword_version, 'screen' );
		}
		//google font
		if ( $shiword_opt['shiword_google_font_family'] ) wp_enqueue_style( 'sw-google-fonts', 'http://fonts.googleapis.com/css?family=' . str_replace( ' ', '+' , $shiword_opt['shiword_google_font_family'] ) );
		//print style
		wp_enqueue_style( 'sw-print-style', get_template_directory_uri() . '/css/print.css', false, $shiword_version, 'print' );
	}
}

if ( !function_exists( 'shiword_ie6_style' ) ) {
	function shiword_ie6_style() {
		?>
			<!--[if lte IE 6]><link media="screen" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie6.css" type="text/css" /><![endif]-->
		<?php
	}
}

function shiword_deregister_styles() {
	wp_deregister_style( 'wp-pagenavi' );
}

// add scripts
if ( !function_exists( 'shiword_scripts' ) ) {
	function shiword_scripts(){
		global $shiword_opt, $shiword_is_printpreview, $shiword_version, $shiword_is_mobile_browser;
		if ( is_admin() ) return;
		if ( $shiword_is_mobile_browser || is_admin() || $shiword_is_printpreview ) return;
		if ($shiword_opt['shiword_jsani'] == 1) {
			wp_enqueue_script( 'sw-animations', get_template_directory_uri().'/js/animations.min.js',array('jquery'),$shiword_version, true ); //shiword js
			if ( $shiword_opt['shiword_sticky'] == 1 ) wp_enqueue_script( 'sw-sticky-slider', get_template_directory_uri().'/js/slider.min.js',array('jquery'),$shiword_version , false );
		}
		wp_enqueue_script( 'sw-audio-player', get_template_directory_uri().'/resources/audio-player/audio-player-noswfobject.js',array('swfobject'),$shiword_version, false ); //audio player
		//thickbox script
		if ( ( $shiword_opt['shiword_thickbox'] == 1 ) ) wp_enqueue_script( 'thickbox' );
	}
}

// detect js
if ( !function_exists( 'shiword_detect_js' ) ) {
	function shiword_detect_js(){
		?>
<script type="text/javascript">
	/* <![CDATA[ */
	(function(){
		var c = document.body.className;
		c = c.replace(/sw-no-js/, 'sw-js');
		document.body.className = c;
	})();
	/* ]]> */
</script>
		<?php
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
		global $shiword_is_mobile_browser;
		if ( $shiword_is_mobile_browser ) {
			if ( is_singular() ) { 
				get_template_part( 'mobile/mobile-single' ); 
			} else {
				get_template_part( 'mobile/mobile-index' );
			}
			exit;
		}
	}
}

// setup for audio player
if ( !function_exists( 'shiword_setup_player' ) ) {
	function shiword_setup_player(){
		global $shiword_is_printpreview, $shiword_colors, $shiword_is_mobile_browser;
		if ( $shiword_is_mobile_browser || is_admin() || $shiword_is_printpreview ) return;
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
		global $shiword_opt, $shiword_is_mobile_browser;
		if ( !is_admin() && ( $shiword_opt['shiword_quotethis'] == 1 ) && !$shiword_is_mobile_browser && is_singular() ) {
		?>
			<script type="text/javascript">
				/* <![CDATA[ */
				if ( document.getElementById('reply-title') && document.getElementById("comment") ) {
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

// Pages Menu (mobile)
if ( !function_exists( 'shiword_navlinks' ) ) {
	function shiword_navlinks( $position = 'top' ) {
		global $shiword_opt, $shiword_is_printpreview;
		if ( !$shiword_opt['shiword_navlinks'] || $shiword_is_printpreview ) return;
		$sep = ( get_next_post() && get_previous_post() ) ? ' - ' : '';
		?>
			<div class="sw-navlinks-<?php echo $position; ?>">
				<?php previous_post_link('<span class="prev">&laquo; %link</span>'); ?>
				<?php echo $sep; ?>
				<?php next_post_link('<span class="next">%link &raquo;</span>'); ?>
			<div class="fixfloat"> </div>
			</div>
		<?php
	}
}

// page hierarchy
if ( !function_exists( 'shiword_multipages' ) ) {
	function shiword_multipages( $r_pos ){
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
				<div class="metafield_trigger mft_hier" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
				<div class="metafield_content">
					<?php
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

//add "like" badges to post/page
if ( !function_exists( 'shiword_I_like_it' ) ) {
	function shiword_I_like_it(){
		global $shiword_opt;
		if ( $shiword_opt['shiword_I_like_it'] == 0 ) return;
		if ( ( $shiword_opt['shiword_I_like_it_plus1'] == 0 ) && ( $shiword_opt['shiword_I_like_it_twitter'] == 0 ) && ( $shiword_opt['shiword_I_like_it_facebook'] == 0 ) && ( $shiword_opt['shiword_I_like_it_linkedin'] == 0 ) && ( $shiword_opt['shiword_I_like_it_stumbleupon'] == 0 ) ) return;
		?>
		<div class="sw-I-like-it">
			<?php if ( $shiword_opt['shiword_I_like_it_plus1']		== 1 ) { ?><div class="sw-I-like-it-button"><div class="g-plusone" data-size="medium" data-href="<?php the_permalink(); ?>"></div></div><?php } ?>
			<?php if ( $shiword_opt['shiword_I_like_it_twitter']		== 1 ) { ?><div class="sw-I-like-it-button"><div class="t-twits"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-count="horizontal"></a></div></div><?php } ?>
			<?php if ( $shiword_opt['shiword_I_like_it_facebook']		== 1 ) { ?><div class="sw-I-like-it-button"><div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-show-faces="false"></div></div><?php } ?>
			<?php if ( $shiword_opt['shiword_I_like_it_linkedin']		== 1 ) { ?><div class="sw-I-like-it-button"><script type="IN/Share" data-url="<?php the_permalink(); ?>" data-counter="right"></script></div><?php } ?>
			<?php if ( $shiword_opt['shiword_I_like_it_stumbleupon']	== 1 ) { ?><div class="sw-I-like-it-button"><script src="http://www.stumbleupon.com/hostedbadge.php?s=1&r=<?php the_permalink(); ?>"></script></div><?php } ?>
			<div class="fixfloat"> </div>
		</div>
		<?php
	}
}

if ( !function_exists( 'shiword_I_like_it_js' ) ) {
	function shiword_I_like_it_js(){
		global $shiword_opt;
?>
<?php if ( $shiword_opt['shiword_I_like_it_plus1'] == 1 ) { ?>
	<script type="text/javascript">
		(function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = '//apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();
	</script>
<?php } ?>

<?php if ( $shiword_opt['shiword_I_like_it_twitter'] == 1 ) { ?>
	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
<?php } ?>

<?php if ( $shiword_opt['shiword_I_like_it_facebook'] == 1 ) { ?>
	<div id="fb-root"></div>
	<script>
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
<?php } ?>

<?php if ( $shiword_opt['shiword_I_like_it_linkedin'] == 1 ) { ?>
	<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
<?php } ?>


<?php
	}
}

// print extra info for posts/pages
if ( !function_exists( 'shiword_extrainfo' ) ) {
	function shiword_extrainfo( $args = array() ) {
		global $shiword_opt;

		//xinfos globally disabled -> return
		if ( $shiword_opt['shiword_xinfos_global'] == 0 ) return;
		
		$defaults = array( 'auth' => 1, 'date' => 1, 'comms' => 1, 'tags' => 1, 'cats' => 1, 'hiera' => 1, 'in_index' => 1 );
		$args = wp_parse_args( $args, $defaults );

		//xinfos disabled in posts index + is index -> exit
		if ( $args['in_index'] && isset( $shiword_opt['shiword_xinfos'] ) && ( $shiword_opt['shiword_xinfos'] == 0 ) ) return;
		
		$r_pos = 10;
		
		// animated xinfos
		if ( $shiword_opt['shiword_xinfos_static'] == 0 ) {
		?>
		<div class="meta_container">
			<div class="meta top_meta ani_meta">
			<?php
			// author
			if ( $args['auth'] && ( $shiword_opt['shiword_byauth'] == 1 ) ) { ?>
				<?php $post_auth = ( $args['auth'] === 1 ) ? '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __( 'View all posts by %s', 'shiword' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' : $args['auth']; ?>
				<div class="metafield_trigger" style="left: 10px;"><?php printf( __( 'by %s', 'shiword' ), $post_auth ); ?></div>
			<?php
			}
			// categories
			if ( $args['cats'] && ( $shiword_opt['shiword_xinfos_cat'] == 1 ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_cat" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php echo __( 'Categories', 'shiword' ) . ': '; ?>
						<?php the_category( ', ' ); ?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			// tags
			if ( $args['tags'] && ( $shiword_opt['shiword_xinfos_tag'] == 1 ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_tag" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php echo __( 'Tags', 'shiword' ) . ': '; ?>
						<?php if ( !get_the_tags() ) { _e( 'No Tags', 'shiword' ); } else { the_tags('', ', ', ''); } ?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			// comments
			$page_cd_nc = ( is_page() && !comments_open() && !have_comments() ); //true if page with comments disabled and no comments
			if ( $args['comms'] && ( $shiword_opt['shiword_xinfos_comm'] == 1 ) && !$page_cd_nc ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_comm" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php echo __( 'Comments', 'shiword' ) . ': '; ?>
						<?php comments_popup_link( __( 'No Comments', 'shiword' ), __( '1 Comment', 'shiword', 'shiword' ), __( '% Comments', 'shiword' ) ); // number of comments?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			// date
			if ( $args['date'] && ( $shiword_opt['shiword_xinfos_date'] == 1 ) ) {
			?>
				<div class="metafield">
					<div class="metafield_trigger mft_date" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
					<div class="metafield_content">
						<?php
						printf( __( 'Published on: %1$s', 'shiword' ), get_the_time( get_option( 'date_format' ) ) );
						?>
					</div>
				</div>
			<?php
				$r_pos = $r_pos + 30;
			}
			// hierarchy
			if ( $args['hiera'] && is_page() && !is_front_page() ) {
			?>
			<?php if ( shiword_multipages( $r_pos ) ) { $r_pos = $r_pos + 30; } ?>
			<?php
			}
			?>
				<div class="metafield_trigger edit_link" style="right: <?php echo $r_pos; ?>px;"><?php edit_post_link( __( 'Edit', 'shiword' ),'' ); ?></div>
			</div>
		</div>
		<?php
		} else { //static xinfos ?>
			<div class="meta">
				<?php if ( $args['auth'] && ( $shiword_opt['shiword_byauth'] == 1 ) ) { printf( __( 'by %s', 'shiword' ), '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( 'View all posts by %s', esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' ); echo '<br />'; }; ?>
				<?php if ( $args['date'] && ( $shiword_opt['shiword_xinfos_date'] == 1 ) ) { printf( __( 'Published on: %1$s', 'shiword' ), get_the_time( get_option( 'date_format' ) ) ) ; echo '<br />'; }?>
				<?php if ( $args['comms'] && ( $shiword_opt['shiword_xinfos_comm'] == 1 ) ) { echo __( 'Comments', 'shiword' ) . ': '; comments_popup_link( __( 'No Comments', 'shiword' ), __( '1 Comment', 'shiword' ), __( '% Comments', 'shiword' ) ); echo '<br />'; } ?>
				<?php if ( $args['tags'] && ( $shiword_opt['shiword_xinfos_tag'] == 1 ) ) { echo __( 'Tags', 'shiword' ) . ': '; if ( !get_the_tags() ) { _e( 'No Tags', 'shiword' ); } else { the_tags('', ', ', ''); }; echo '<br />';  } ?>
				<?php if ( $args['cats'] && ( $shiword_opt['shiword_xinfos_cat'] == 1 ) ) { echo __( 'Categories', 'shiword' ) . ': '; the_category( ', ' ); echo '<br />'; } ?>
				<?php edit_post_link( __( 'Edit', 'shiword' ) ); ?>
			</div>
		<?php
		}
	}
}

// Search reminder
if ( !function_exists( 'shiword_search_reminder' ) ) {
	function shiword_search_reminder() {
		global $shiword_opt;
		$type = '';
		if ( is_archive() ) {
		
			if ( is_category() )	$type = __( 'Category', 'shiword' );
			elseif ( is_tag() )		$type = __( 'Tag', 'shiword' );
			elseif ( is_date() )	$type = __( 'Date', 'shiword' );
			elseif ( is_author() )	$type = __( 'Author', 'shiword' );
			elseif ( is_tax() )		$type = __( 'Taxonomy', 'shiword' );
			
			if ( $type ) $type = sprintf( 'Archive for %1$s : %2$s', $type, '<span class="sw-search-term">' . wp_title( '',false ) . '</span>');
			
		} elseif ( is_search() ) {
		
			$type = sprintf( __( 'Search results for &#8220;%s&#8221;', 'shiword' ), '<span class="sw-search-term">' . esc_html( get_search_query() ) . '</span>' );
			
		}
		if ( $type ) { 
			?>
			<div class="meta sw-search-reminder">
				<p><?php echo $type; ?></p>
			</div>
			<?php
		}
		
		if ( ( $shiword_opt['shiword_cat_description'] == 1 ) && is_category() && category_description() ) { 
			?>
			<div class="meta">
				<p><?php echo category_description(); ?></p>
			</div>
			<?php
		}
		
		if ( is_author() ) shiword_post_details( true, false, false, false );

	}
}

// add a fix for embed videos
if ( !function_exists( 'shiword_wmode_transparent' ) ) {
	function shiword_wmode_transparent($html, $url, $attr) {
		if ( strpos( $html, '<embed ' ) !== false ) {
			$html = str_replace('</param><embed', '</param><param name="wmode" value="transparent"></param><embed', $html);
			$html = str_replace('<embed ', '<embed wmode="transparent" ', $html);
			return $html;
		} elseif ( strpos ( $html, 'feature=oembed' ) !== false )
			return str_replace( 'feature=oembed', 'feature=oembed&wmode=transparent', $html );
		else
			return $html;
	}
}

// Get first image of a post
if ( !function_exists( 'shiword_get_first_image' ) ) {
	function shiword_get_first_image() {
		global $post, $posts;
		$first_info = array( 'img' => '', 'title' => '', 'src' => '',);
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
		} else {
			return false;
		}
	}
}

// Get first link of a post
if ( !function_exists( 'shiword_get_first_link' ) ) {
	function shiword_get_first_link() {
		global $post, $posts;
		$first_info = array( 'anchor' => '', 'title' => '', 'href' => '', 'text' => '' );
		//search the link in post content
		preg_match_all( "/<a\b[^>]*>(.*?)<\/a>/i",$post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_info['anchor'] = $result[0][0];
			$first_info['text'] = isset( $result[1][0] ) ? $result[1][0] : '';
			//get the title (if any)
			preg_match_all( '/(title)=(["\'][^"]*["\'])/i',$first_info['anchor'], $link_title );
			$first_info['title'] = isset( $link_title[2][0] ) ? str_replace( array('"','\''),'',$link_title[2][0] ) : '';
			//get the path
			preg_match_all( '/(href)=(["\'][^"]*["\'])/i',$first_info['anchor'], $link_href );
			$first_info['href'] = isset( $link_href[2][0] ) ? str_replace( array('"','\''),'',$link_href[2][0] ) : '';
			return $first_info;
		} else {
			return false;
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
		} else {
			return false;
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
	function shiword_get_the_thumb( $id, $size_w, $size_h, $class, $default = '', $linked = false ) {
		if ( has_post_thumbnail( $id ) ) {
			$output = get_the_post_thumbnail( $id, array( $size_w,$size_h ), array( 'class' => $class ) );
		} else {
			if ( shiword_is_post_format_available( $id ) ) {
				$format = get_post_format( $id );
			} else {
				$format = 'thumb';
			}
			$output = '<img class="' . $class . ' wp-post-image" width="' . $size_w . '" height="' . $size_h . '" alt="thumb" src="' . get_template_directory_uri() . '/images/thumbs/' . $format . '.png" />';
		}
		if ( $linked )
			return '<a href="' . get_permalink( $id ) . '" rel="bookmark">' . $output . '</a>';
		else
			return $output;
	}
}

// display the post title with the featured image
if ( !function_exists( 'shiword_post_title' ) ) {
	function shiword_post_title( $args = array() ) {
		global $post, $shiword_opt;

		$defaults = array( 'alternative' => '', 'fallback' => '', 'featured' => 0, 'href' => get_permalink(), 'target' => '', 'title' => the_title_attribute( array('echo' => 0 ) ), 'extra' => '' );
		$args = wp_parse_args( $args, $defaults );

		$post_title = $args['alternative'] ? $args['alternative'] : get_the_title();
		$post_title = $post_title ? $post_title : $args['fallback'];
		
		$link_target = $args['target'] ? ' target="'.$args['target'].'"' : '';
		
		$has_format = shiword_is_post_format_available( $post->ID );
		$has_featured_image = $args['featured'] && ( $shiword_opt['shiword_supadupa_title'] == 1 ) && has_post_thumbnail( $post->ID ) && ( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) && $image[1] >= 700 && $image[2] >= 200;
		
		$title_class = ( $has_format && !$has_featured_image ) ? ' sw-icon-' . get_post_format( $post->ID ) : '' ;

		if ( $post_title ) $post_title = '<h2 class="storytitle' . $title_class . '">' . $args['extra'] . '<a title="' . $args['title'] . '" href="' . $args['href'] . '"' . $link_target . ' rel="bookmark">' . $post_title . '</a></h2>';

		if ( $has_featured_image ) {
			?>
			<div class="storycontent sd-post-title">
				<?php echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' ); ?>
				<?php echo $post_title; ?>
			</div>
			<?php
		} else {
			echo $post_title;
		}
	}
}

// display post details
if ( !function_exists( 'shiword_post_details' ) ) {
	function shiword_post_details( $auth, $date, $tags, $cats, $hiera = false, $av_size = 48, $featured = false ) {
		global $post;
		?>
		<?php if ( $featured &&  has_post_thumbnail( $post->ID ) ) { echo '<div class="sw-post-details-thumb">' . get_the_post_thumbnail( $post->ID, 'thumbnail' ) . '</div>'; } ?>
		<?php if ( $auth ) {
			$author = $post->post_author;

			$name = get_the_author_meta( 'nickname', $author );
			$alt_name = get_the_author_meta( 'user_nicename', $author );
			$avatar = get_avatar( $author, $av_size, 'Gravatar Logo', $alt_name.'-photo' );
			$description = get_the_author_meta( 'description', $author );
			$author_link = get_author_posts_url( $author );

			?>
			<div class="sw-author-bio vcard">
				<?php echo $avatar; ?>
				<a class="fn author-name" href="<?php echo $author_link; ?>" ><?php echo $name; ?></a>
				<?php if ( $description ) { ?><p class="author-description note"><?php echo $description; ?> </p><?php } ?>
				<div class="fixfloat"></div>
			<?php if ( get_the_author_meta( 'twitter', $author ) || get_the_author_meta( 'facebook', $author ) ) { ?>
				<p class="author-social">
					<?php if ( get_the_author_meta( 'twitter', $author ) ) echo '<a target="_blank" class="url" title="' . sprintf( __( 'follow %s on Twitter', 'shiword' ), $name ) . '" href="' . get_the_author_meta( 'twitter', $author ) . '"><img alt="twitter" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/Twitter.png" /></a>'; ?>
					<?php if ( get_the_author_meta( 'facebook', $author ) ) echo '<a target="_blank" class="url" title="' . sprintf( __( 'follow %s on Facebook', 'shiword' ), $name ) . '" href="' . get_the_author_meta( 'facebook', $author ) . '"><img alt="facebook" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/Facebook.png" /></a>'; ?>
				</p>
			<?php } ?>
			</div>
		<?php } ?>
		<?php if ( $cats ) { echo __( 'Categories', 'shiword' ) . ': '; the_category( ', ' ); echo '<br/>'; } ?>
		<?php if ( $tags ) { echo __( 'Tags', 'shiword' ) . ': '; if ( !get_the_tags() ) { _e( 'No Tags', 'shiword' ); } else { the_tags('', ', ', ''); } echo '<br/>'; } ?>
		<?php if ( $date ) { printf( __( 'Published on: %1$s', 'shiword' ), get_the_time( get_option( 'date_format' ) ) ); } ?>
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
								<div class="storycontent">
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
		// Make theme available for translation
		load_theme_textdomain( 'shiword', get_template_directory() . '/languages' );

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
		define( 'HEADER_IMAGE_WIDTH', $shiword_opt['shiword_frame_width'] );

		$head_h = ( isset( $shiword_opt['shiword_head_h'] ) ? str_replace( 'px', '', $shiword_opt['shiword_head_h']) : 100 );

		define( 'HEADER_IMAGE_HEIGHT', $head_h );

		// Don't support text inside the header image.
		define( 'NO_HEADER_TEXT', true );

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See shiword_admin_header_style(), below.
		add_custom_image_header( 'shiword_header_style', 'shiword_admin_header_style' );

		// Add a way for the custom background to be styled in the admin panel that controls
		if ( isset( $shiword_opt['shiword_custom_bg'] ) && $shiword_opt['shiword_custom_bg'] == 1 ) {
			shiword_add_custom_background( 'shiword_custom_bg_plus' , '' , '' );
		} else {
			set_theme_mod('background_image', '');
			set_theme_mod('background_image_thumb', '');
			add_custom_background( 'shiword_custom_bg' , '' , '' );
		}

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

// get lightness of from rgb color
if ( !function_exists( 'shiword_rgblight' ) ) {
	function shiword_rgblight($hex) {
		$color = str_replace('#','',$hex);
		$rgba['r'] = hexdec(substr($color,0,2));
		$rgba['g'] = hexdec(substr($color,2,2));
		$rgba['b'] = hexdec(substr($color,4,2));

		$var_min = min( $rgba['r'], $rgba['g'], $rgba['b'] );	//min. value of rgb
		$var_max = max( $rgba['r'], $rgba['g'], $rgba['b'] );	//max. value of rgb

		$lightness = ( $var_max + $var_min ) / 2;
		
		return $lightness;
	}
}

// custom header image style - gets included in the site header
if ( !function_exists( 'shiword_header_style' ) ) {
	function shiword_header_style() {
		global $shiword_colors, $shiword_is_mobile_browser, $shiword_opt;
		if ( $shiword_is_mobile_browser ) return;
		$device_rgba = shiword_hex2rgba( $shiword_colors['device_color'], $shiword_colors['device_opacity']);
	    ?>
	<style type="text/css">
		body {
			font-size: <?php echo $shiword_opt['shiword_font_size']; ?>;
			<?php if ( $shiword_opt['shiword_google_font_family'] && $shiword_opt['shiword_google_font_body'] ) { ?>
				font-family: <?php echo $shiword_opt['shiword_google_font_family']; ?>;
			<?php } else { ?>
				font-family: <?php echo $shiword_opt['shiword_font_family']; ?>;
			<?php } ?>
		}
		<?php if ( $shiword_opt['shiword_google_font_family'] && $shiword_opt['shiword_google_font_post_title'] ) { ?>
		h2.storytitle {
			font-family: <?php echo $shiword_opt['shiword_google_font_family']; ?>;
		}
		<?php } ?>
		<?php if ( $shiword_opt['shiword_google_font_family'] && $shiword_opt['shiword_google_font_post_content'] ) { ?>
		.storycontent {
			font-family: <?php echo $shiword_opt['shiword_google_font_family']; ?>;
		}
		<?php } ?>
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
		.minib_img,
		.menuitem_img {
			background-image: url('<?php echo get_template_directory_uri(); ?>/images/minibuttons-<?php echo $shiword_colors['device_button_style']; ?>.png');
		}
		body {
			font-size: <?php echo $shiword_opt['shiword_font_size']; ?>;
			<?php if ( $shiword_opt['shiword_google_font_family'] && $shiword_opt['shiword_google_font_body'] ) { ?>
				font-family: <?php echo $shiword_opt['shiword_google_font_family']; ?>;
			<?php } else { ?>
				font-family: <?php echo $shiword_opt['shiword_font_family']; ?>;
			<?php } ?>
		}
		.TB_overlayBG,
		#TB_window,
		#TB_load {
			background-color: <?php echo $shiword_opt['shiword_thickbox_bg']; ?>;
		}
		#TB_window {
			color: <?php echo shiword_rgblight($shiword_opt['shiword_thickbox_bg']) < 125 ? '#fff' : '#000'; ?>;
		}
		#TB_secondLine {
			color: <?php echo shiword_rgblight($shiword_opt['shiword_thickbox_bg']) < 125 ? '#c0c0c0' : '#404040'; ?>;
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
		.storycontent img.size-full,
		.sd-post-title img {
			width:auto;
		}
	</style>
	<![endif]-->
	  <?php
	}
}

// custom background style (enhanced) - gets included in the site header
if ( !function_exists( 'shiword_custom_bg_plus' ) ) {
	function shiword_custom_bg_plus() {
		global $shiword_is_printpreview, $shiword_is_mobile_browser;
		if ( $shiword_is_printpreview || $shiword_is_mobile_browser ) return;

		$background = get_background_image();
		$color = get_background_color();
		if ( ! $background && ! $color ) return;
	
		$style = $color ? "background-color: #$color;" : '';
	
		if ( $background ) {
			$style .= " background-image: url('$background');";
		}
		?>
		<style type="text/css"> 
			body { <?php echo trim( $style ); ?> }
			#fixedfoot_cont { <?php echo trim( $style ); ?> }
			#head_cont { background-color: #<?php echo trim( $color ); ?>; }
		</style>
		<?php
	}
}

// custom background style - gets included in the site header
if ( !function_exists( 'shiword_custom_bg' ) ) {
	function shiword_custom_bg() {
		global $shiword_is_printpreview, $shiword_is_mobile_browser;
		if ( $shiword_is_printpreview || $shiword_is_mobile_browser ) return;

		$color = get_background_color();
		if ( ! $color ) return;
		?>
		<style type="text/css"> 
			body { background-color: #<?php echo trim( $color ); ?>; }
			#head_cont { background-color: #<?php echo trim( $color ); ?>; }
		</style>
		<?php
	}
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

// add links to admin bar
if ( !function_exists( 'shiword_admin_bar_plus' ) ) {
	function shiword_admin_bar_plus() {
		global $wp_admin_bar, $shiword_opt;
		if (!is_super_admin() || !is_admin_bar_showing())
			return;
		$add_menu_meta = array(
			'target'    => '_blank'
		);
		// slideshow
		if ( $shiword_opt['shiword_sticky'] == 1 ) {
			$wp_admin_bar->add_menu(array(
				'id'        => 'sw_slideshow',
				'parent'    => 'appearance',
				'title'     => __('Slideshow','shiword'),
				'href'      => get_admin_url() . 'themes.php?page=tb_shiword_slideshow',
				'meta'      => $add_menu_meta
			));
		}
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

// check if the current post has a format and if it's available
if ( !function_exists( 'shiword_is_post_format_available' ) ) {
	function shiword_is_post_format_available( $id ) {
		global $shiword_opt;
		$is_available = function_exists( 'get_post_format' ) && get_post_format( $id ) && isset( $shiword_opt['shiword_postformat_' . get_post_format( $id ) ] ) && $shiword_opt['shiword_postformat_' . get_post_format( $id ) ] == 1;
		return $is_available;
	}
}

//Displays the amount of time since a post or page was written in a nice friendly manner.
//Based on Plugin: Date in a nice tone (http://wordpress.org/extend/plugins/date-in-a-nice-tone/)
if ( !function_exists( 'shiword_friendly_date' ) ) {
	function shiword_friendly_date() {
			
		$posttime = get_the_time('U');
		$currenttime = time();
		$timedifference = $currenttime - $posttime;
		
		$mininsecs = 60;
		$hourinsecs = 3600;
		$dayinsecs = 86400;
		$monthinsecs = $dayinsecs * 31;
		$yearinsecs = $dayinsecs * 366;

		//if over 2 years
		if ($timedifference > ($yearinsecs * 2)) {
			$datewithnicetone = __( 'quite a long while ago...', 'shiword' );

		//if over a year 
		} else if ($timedifference > $yearinsecs) {
			$datewithnicetone = __( 'over a year ago', 'shiword' );

		//if over 2 months
		} else if ($timedifference > ($monthinsecs * 2)) {
			$num = round($timedifference / $monthinsecs);
			$datewithnicetone = sprintf(__('%s months ago', 'shiword' ),$num);
		
		//if over a month	
		} else if ($timedifference > $monthinsecs) {
			$datewithnicetone = __( 'a month ago', 'shiword' );
				   
		//if more than 2 days ago
		} else {
			$htd = human_time_diff( get_the_time('U'), current_time('timestamp') );
			$datewithnicetone = sprintf(__('%s ago', 'shiword' ), $htd );
		} 
		
		echo $datewithnicetone;
			
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
	global $post, $wp_locale, $shiword_opt;

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

	if ( $shiword_opt['shiword_thickbox_link_to_image'] == 1 ) $attr['link'] = 'file';

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

?>

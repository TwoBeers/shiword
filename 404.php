<?php get_header(); ?>
<?php $sw_website = home_url(); ?>

<?php
	$sw_use_side = ( $shiword_opt['shiword_rsideb'] == 0 ) ? false : true; 
	$sw_postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $sw_postswidth; ?> letsstick">

	<div class="post error404 not-found" id="post-0">
		<h2 class="storytitle">Error 404 - <?php _e( 'Page not found','shiword' ); ?></h2>
		<p><?php _e( "Sorry, you're looking for something that isn't here" ,'shiword' ); ?>: <u><?php echo home_url() . esc_html( $_SERVER['REQUEST_URI'] ); ?></u></p><br/>
		<?php if ( is_active_sidebar( '404-widgets-area' ) ) { ?>
			<p><?php _e( 'Here is something that might help:','shiword' ); ?></p>
			<div id="error404-widget-area">
				<?php dynamic_sidebar( '404-widgets-area' ); ?>
			</div>
		<?php } else { ?>
			<p><?php _e( "There are several links scattered around the page, maybe they can help you on finding what you're looking for.", 'shiword' ); ?></p>
			<p><?php _e( 'Perhaps using the search form will help too...', 'shiword' ); ?></p>
			<?php get_search_form(); ?>
		<?php } ?>
		<div class="fixfloat"> </div>
	</div>
</div>

<?php if ( $sw_use_side ) shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
<?php get_header(); ?>
<?php $website = home_url(); ?>

<?php
	$sw_use_side = ( $shiword_opt['shiword_rsideb'] == 0 ) ? false : true; 
	$postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $postswidth; ?> letsstick">

	<div class="post error404 not-found" id="post-0">
		<div class="meta" style="text-align: center;"><h2 class="storytitle">Error 404 - <?php _e( 'Page not found', 'shiword' ); ?></h2></div>
		<div class="storycontent">
			<p><?php _e( 'Sorry, you&lsquo;re looking for something that isn&lsquo;t here', 'shiword' ); ?>: <u><?php echo " ".$website.esc_url( $_SERVER['REQUEST_URI'] ); ?></u></p>
			<p><?php _e( 'Perhaps using the search form will help...', 'shiword' ); ?></p>
			<div style="width: 250px;"><?php get_search_form(); ?></div>
		</div>
		<div class="fixfloat"> </div>

	</div>
</div>

<?php if ( $sw_use_side ) get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

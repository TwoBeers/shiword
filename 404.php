<?php get_header();

$website = home_url();

?>

<div class="post" id="post-404-not-found">
	<div class="wp-caption aligncenter"><h2 class="storytitle">Error 404 - <?php _e( 'Page not found' ); ?></h2></div>
	<div class="storycontent">
		<p><?php _e( 'Sorry, you&lsquo;re looking for something that isn&lsquo;t here', 'shiword' ); ?>: <u><?php echo " ".$website.esc_url( $_SERVER['REQUEST_URI'] ); ?></u></p>
		<p><?php _e( 'You can try the following:', 'shiword' ); ?></p>
		<ul>
			<li><?php _e( 'search the site using the searchbox in the upper-right', 'shiword' ); ?></li>
			<li><?php _e( 'see the suggested pages in the above menu', 'shiword' ); ?></li>
			<li><?php _e( 'browse the site throught the navigation bar below', 'shiword' ); ?></li>
		</ul>
	</div>
	<div class="fixfloat"> </div>

</div>


<?php get_footer(); ?>

<?php global $shiword_opt, $shiword_is_allcat_page, $shiword_is_printpreview; ?>

<!-- begin footer -->

		<?php $sw_headmenu = wp_nav_menu( array( 'echo' => 0, 'menu_id' => 'bottommenu', 'fallback_cb' => false, 'theme_location' => 'secondary', 'depth' => 1 ) ); ?>
		<?php if ( $sw_headmenu ) echo '<div class="sw-menu">' . $sw_headmenu . '<div class="fixfloat"></div></div>' ?>

		<?php shiword_hook_before_footer(); ?>
		<div id="footer">
			<?php get_sidebar( 'footer' ); // show footer widgets areas ?>
			<?php shiword_hook_footer(); ?>
			<div id="themecredits">
				&copy; <?php echo date( 'Y' ); ?>
				<strong><?php bloginfo( 'name' ); ?></strong>
				<?php if ( $shiword_opt['shiword_tbcred'] == 1 ) { ?>
					<a href="http://www.twobeers.net/" title="Shiword theme<?php global $shiword_version; if( !empty( $shiword_version ) ) { echo ' v' . $shiword_version; } ?> by TwoBeers Crew">
						<img alt="twobeers.net" src="<?php echo esc_url( get_template_directory_uri() . '/images/tb_micrologo.png' ); ?>" />
					</a>
					<a href="http://wordpress.org/" title="<?php _e( 'Powered by WordPress', 'shiword' ); ?>">
						<img alt="WordPress" src="<?php echo esc_url( get_template_directory_uri() . '/images/wp_micrologo.png' ); ?>" />
					</a>
				<?php } ?>
			</div>
			<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
		</div>
		<?php shiword_hook_after_footer(); ?>
	</div> <!-- end #maincontent -->

	<?php shiword_fixed_footer(); ?>
	
	<?php if ( $shiword_is_printpreview ) { ?>
	<div id="close_preview">
		<a id="close_button" title="<?php _e( 'Close','shiword' ); ?>" href="<?php echo get_permalink( $post->ID ); ?>"><?php _e( 'Close','shiword' ); ?></a>
		<a href="javascript:window.print()" title="<?php _e( 'Print','shiword' ); ?>" id="print_button" class="hide-if-no-js"><?php _e( 'Print','shiword' ); ?></a>
	</div>
	<?php } ?>
</div> <!-- end #main -->


<div id="footer-hook-cont">
		<!-- info: 
			<?php 
				global $shiword_version; 
				echo ' | WP version - ' . get_bloginfo ( 'version' );
				echo ' | WP language - ' . get_bloginfo ( 'language' );
				foreach ( $shiword_opt as $key => $val ) { echo ' | ' . $key . ' - ' . $val; };
			?>
		-->
	<?php wp_footer(); ?>
</div>
</body>
</html>
<?php
/**
 * footer.php
 *
 * Template part file that contains the site footer and
 * closing HTML body elements
 *
 * @package Shiword
 * @since 1.00
 */
?>

			<?php wp_nav_menu( array( 'container_class' => 'sw-menu', 'menu_id' => 'bottommenu', 'fallback_cb' => false, 'theme_location' => 'secondary', 'depth' => 1 ) ); ?>

			<?php shiword_hook_footer_before(); ?>

			<!-- begin footer -->

			<div id="footer">

				<?php shiword_get_sidebar( 'footer' ); // show footer widgets areas ?>

				<?php shiword_hook_footer_top(); ?>

				<div id="themecredits">
					<?php shiword_theme_credits(); ?>
				</div>

				<?php shiword_hook_footer_bottom(); ?>

				<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

			</div>

			<!--[if lte IE 7]>
			<div style="background:#e29808;color:#fff;padding:10px;">
				It looks like you're using an old and insecure version of Internet Explorer. Using an outdated browser makes your computer unsafe. For the best WordPress experience, please update your browser
			</div>
			<![endif]-->

			<?php shiword_hook_footer_after(); ?>

		</div> <!-- end #maincontent -->

		<?php shiword_fixed_footer(); ?>

		<?php if ( shiword_is_printpreview() ) { ?>
		<div id="close_preview">
			<a id="close_button" title="<?php _e( 'Close','shiword' ); ?>" href="<?php echo get_permalink( $post->ID ); ?>"><?php _e( 'Close','shiword' ); ?></a>
			<a href="javascript:window.print()" title="<?php _e( 'Print','shiword' ); ?>" id="print_button" class="hide-if-no-js"><?php _e( 'Print','shiword' ); ?></a>
		</div>
		<?php } ?>

	</div> <!-- end #main -->

	<?php shiword_hook_body_bottom(); ?>

	<div id="footer-hook-cont">
		<?php wp_footer(); ?>
	</div>
</body>
</html>
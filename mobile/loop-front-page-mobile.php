<?php
/**
 * The mobile theme - Front Page template
 *
 * No title, no comments, no navigation, just page content.
 *
 * @package Shiword
 * @subpackage mobile
 * @since 3.03
 */


locate_template( array( 'mobile/header-mobile.php' ), true, false ); ?>

<?php if ( have_posts() ) {

	while ( have_posts() ) {

		the_post(); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php the_content(); ?>
			<?php wp_link_pages( 'before=<div class="tbm-pc-navi">' . __( 'Pages', 'shiword' ) . ':&after=</div>' ); ?>
		</div>

	<?php } ?>

<?php } else { ?>

	<p class="tbm-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>

<?php } ?>

<?php locate_template( array( 'mobile/footer-mobile.php' ), true, false ); ?>

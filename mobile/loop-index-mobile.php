<?php
/**
 * The mobile theme - Index/Arcive/Search/404 template
 *
 * @package Shiword
 * @subpackage mobile
 * @since 3.03
 */


locate_template( array( 'mobile/header-mobile.php' ), true, false ); ?>

<?php if ( have_posts() ) { ?>

	<?php do_action( 'shiword_mobile_hook_content_before' ); ?>

	<ul class="tbm-group">

	<?php while ( have_posts() ) {

		the_post(); ?>

		<li>

			<a href="<?php the_permalink() ?>" rel="bookmark">

				<?php if ( has_post_thumbnail() ) { ?>

					<?php the_post_thumbnail( array( 32, 32 ), array( 'class' => 'tb-thumb-format' ) ); ?>

				<?php } else { ?>

					<span class="tb-thumb-format <?php echo get_post_format() ? get_post_format() : 'standard'; ?>"></span>

				<?php } ?>

				<?php the_title(); ?>

				<br>

				<span class="tbm-details"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php comments_number('(0)', '(1)','(%)'); ?></span>

			</a>

		</li>

	<?php } ?>

	</ul>

	<?php do_action( 'shiword_mobile_hook_content_after' ); ?>

<?php } else { ?>

		<p class="tbm-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>

<?php } ?>

<?php locate_template( array( 'mobile/footer-mobile.php' ), true, false ); ?>

<?php
/**
 * post-status.php
 *
 * Template part file that contains the Status Format entry
 * 
 * @package Shiword
 * @since 2.07
 */
?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">

	<div class="sw-status-image no-grav">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_avatar( $post->post_author, 50, $default=get_option('avatar_default'), get_the_author() ); ?></a>
	</div>

	<div class="post-body">

		<?php shiword_hook_entry_top(); ?>

		<div class="storycontent">

			<?php the_content(); ?>

			<div class="fixfloat sw-status-info">
				<a class="sw-author" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'View all posts by %s', 'shiword' ), get_the_author() ) ); ?>"><?php the_author(); ?></a>
				<span class="sw-date"><?php echo ' - ' . shiword_get_friendly_date(); ?></span>
				<?php edit_post_link( __( 'Edit', 'shiword' ),' - ' ); ?>
			</div>

		</div>

		<?php shiword_hook_entry_bottom(); ?>

	</div>

	<?php shiword_hook_like_it(); ?>

	<div class="fixfloat"> </div>

</div>
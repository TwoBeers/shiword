<?php
/**
 * post-image.php
 *
 * Template part file that contains the Image Format entry
 * 
 * @package Shiword
 * @since 2.07
 */
?>

<?php
	$shiword_first_img = shiword_get_first_image();
?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">

	<?php shiword_thumb(); ?>

	<div class="post-body">

		<?php shiword_hook_entry_top(); ?>

		<?php shiword_post_title( array( 'alternative' => $shiword_first_img ? $shiword_first_img['title'] : '' ) ); ?>

		<?php shiword_hook_like_it(); ?>

		<?php shiword_extrainfo(); ?>

		<div class="storycontent">

			<?php

				if ( ( shiword_get_opt( 'shiword_xcont' ) == 1 ) || is_archive() || is_search() ) { // compact view

					if ( $shiword_first_img )
						echo '<a href="' . esc_url( $shiword_first_img['src'] ) . '" target="_blank" title="' . esc_attr( $shiword_first_img['title'] ) . '"><img style="max-height: ' . get_option('medium_size_w') . 'px; max-width: ' . get_option('medium_size_h') . 'px;" title="' . esc_attr( $shiword_first_img['title'] ) . '" src="' . esc_url( $shiword_first_img['src'] ) . '" /></a>';
					else
						the_excerpt();

				} else { // normal view

					if ( $shiword_first_img )
						echo '<a href="' . esc_url( $shiword_first_img['src'] ) . '" target="_blank" title="' . esc_attr( $shiword_first_img['title'] ) . '">' . $shiword_first_img['img'] . '</a>';
					else
						the_content();

				}

			?>

		</div>

		<?php shiword_hook_entry_bottom(); ?>

	</div>

	<div class="fixfloat"> </div>

</div>

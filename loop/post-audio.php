<?php
/**
 * post-audio.php
 *
 * Template part file that contains the Audio Format entry
 * 
 * @package Shiword
 * @since 2.07
 */
?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">

	<?php shiword_thumb(); ?>

	<div class="post-body">

		<?php shiword_hook_entry_top(); ?>

		<?php shiword_post_title(); ?>

		<?php shiword_hook_like_it(); ?>

		<?php shiword_extrainfo(); ?>

		<div class="storycontent">

			<?php if ( ( shiword_get_opt( 'shiword_xcont' ) == 0 ) && !is_archive() && !is_search() ) // normal view
				the_content();
			?>

		</div>

		<?php shiword_hook_entry_bottom(); ?>

	</div>
	<div class="fixfloat"> </div>
</div>

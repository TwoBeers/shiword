<?php
/**
 * post-protected.php
 *
 * Template part file that contains the Protected entry
 * 
 * @package Shiword
 * @since 2.07
 */
?>

<div <?php post_class( 'sw-entry-standard' ) ?> id="post-<?php the_ID(); ?>">

	<?php shiword_thumb(); ?>

	<div class="post-body">

		<?php shiword_hook_entry_top(); ?>

		<?php shiword_post_title(); ?>

		<div class="storycontent">

			<?php the_content(); ?>

		</div>

		<?php shiword_hook_entry_bottom(); ?>

	</div>

	<div class="fixfloat"> </div>

</div>

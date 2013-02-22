<?php
/**
 * index.php
 *
 * This file is the master/default template file, used for Index/Archive/Search
 *
 * @package shiword
 * @since shiword 1.00
 */
?>

<?php get_header(); ?>

<?php shiword_get_layout(); ?>

<?php shiword_hook_content_before(); ?>

<div id="posts-container" class="<?php shiword_content_class(); ?>">

	<?php shiword_hook_content_top(); ?>

	<?php shiword_search_reminder(); ?>

	<?php get_template_part( 'loop', 'index' ); ?>

	<?php shiword_hook_content_bottom(); ?>

</div>

<?php shiword_hook_content_after(); ?>

<?php shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

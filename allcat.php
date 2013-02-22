<?php
/**
 * allcat.php
 *
 * The template file used to display the whole category list 
 * as a page.
 *
 * @package shiword
 * @since shiword 1.00
 */
?>

<?php get_header(); ?>

<?php shiword_get_layout(); ?>

<div class="<?php shiword_content_class(); ?>">

	<div class="post">

		<h2 class="storytitle"><?php _e( 'Categories', 'shiword' ); ?></h2>

		<div class="meta_container">
			<div class="meta top_meta">
				<?php _e( 'All Categories', 'shiword' ); ?>
			</div>
		</div>

		<div class="storycontent">
			<ul>
				<?php wp_list_categories( 'title_li=' ); ?>
			</ul>
		</div>

	</div>

</div>

<?php shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

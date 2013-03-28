<?php
/**
 * sidebar-primary.php
 *
 * Template part file that contains the primary sidebar content
 *
 * @package Shiword
 * @since 4.00
 */
?>

<?php shiword_hook_sidebars_before( 'primary' ); ?>

<!-- begin primary sidebar -->

<div id="sidebardx">

	<?php shiword_hook_sidebar_top( 'primary' ); ?>

	<div id="sidebarbody">
		<?php if ( !dynamic_sidebar( 'primary-widget-area' ) ) { ?>

			<div id="sw-search">
				<?php get_search_form(); ?>
			</div>

			<div id="w_meta" class="widget">
				<div class="w_title"><?php _e( 'Meta', 'shiword' ); ?></div>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</div>

			<div id="w_pages" class="widget">
				<div class="w_title"><?php _e( 'Pages', 'shiword' ); ?></div>
				<ul>
					<?php wp_list_pages( 'title_li=' ); ?>
				</ul>
			</div>

			<div id="w_bookmarks" class="widget">
				<div class="w_title"><?php _e( 'Blogroll', 'shiword' ); ?></div>
				<ul>
					<?php wp_list_bookmarks( 'title_li=0&categorize=0' ); ?>
				</ul>
			</div>

			<div id="w_categories" class="widget">
				<div class="w_title"><?php _e( 'Categories', 'shiword' ); ?></div>
				<ul>
					<?php wp_list_categories( 'title_li=' ); ?>
				</ul>
			</div>

			<div id="w_archives" class="widget">
				<div class="w_title"><?php _e( 'Archives', 'shiword' ); ?></div>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
				</div>
		<?php } ?>
	</div>

	<?php shiword_hook_sidebar_bottom( 'primary' ); ?>

</div>

<!-- end primary sidebar -->

<?php shiword_hook_sidebars_after( 'primary' ); ?>

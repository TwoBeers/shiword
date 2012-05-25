<?php get_header(); //shows "all categories" page ?>
<?php
	global $shiword_opt;
	$sw_use_side = ( $shiword_opt['shiword_rsideb'] == 0 ) ? false : true; 
	$sw_postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $sw_postswidth; ?>">
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

<?php if ( $sw_use_side ) shiword_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>

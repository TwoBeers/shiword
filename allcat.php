<?php
get_header(); //shows "all categories" page.
?>
<div class="post">

	<h2 class="storytitle"><?php _e( 'Categories', 'shiword' ); ?></h2>

	<div style="position: relative; margin-right: 12px;">
		<div class="comment_tools top_meta">
			<?php _e( 'All Categories', 'shiword' ); ?>
		</div>
	</div>

	<div class="storycontent">
		<ul>
			<?php wp_list_categories( 'title_li=' ); ?>
		</ul>
	</div>

</div>

<?php
get_footer();
?>

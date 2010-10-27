<?php get_header(); ?>
<?php global $shiword_opt; ?>
<?php // search reminder
	if (is_category()) {
		echo '<div class="wp-caption aligncenter"><p class="wp-caption-text">' . __('Search Results') . ' <strong>' . __('Category') . ': '; wp_title('',true,'right'); echo ' </strong></p></div>'; 
	}
	elseif (is_tag()) {
		echo '<div class="wp-caption aligncenter"><p class="wp-caption-text">' . __('Search Results') . ' <strong>' . __('Tag') . ': '; wp_title('',true,'right'); echo ' </strong></p></div>'; 
	}
	elseif (is_date()) {
		echo '<div class="wp-caption aligncenter"><p class="wp-caption-text">' . __('Search Results') . ' <strong>' . __('Archives') . ': '; wp_title('',true,'right'); echo ' </strong></p></div>'; 
	}
	elseif (is_search()) {
		printf('<div class="wp-caption aligncenter"><p class="wp-caption-text">' . __('Search results for &#8220;%s&#8221;') . '</p></div>', '<strong>' . esc_html( get_search_query() ) . '</strong>');
	}
?>
<?php if ( ($shiword_opt['shiword_sticky'] == 'true') && is_home()  ) query_posts('caller_get_posts=1'); // if stycky slideshow is visible, ignore the sticky attribute ?>
<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		<div <?php post_class('sw-pthumb-'.$shiword_opt['shiword_pthumb']) ?> id="post-<?php the_ID(); ?>">
			<?php
			// Post thumbnail
				if( $shiword_opt['shiword_pthumb'] =='true' ) {
					if(has_post_thumbnail()) {
						the_post_thumbnail(array(120,120), array('class' => 'alignleft'));
					} else {
						echo '<img class="alignleft wp-post-image" alt="thumb" src="'.get_bloginfo('stylesheet_directory').'/images/thumb_120.png" />';
					}
				}
			?>
			<div class="post-body">
				<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
					$post_title = the_title('','',false);
					if (!$post_title) {
						_e('(no title)');
					} else {
						echo $post_title;
					}
					?></a>
				</h2>
				<div class="meta_container">
					<div class="meta top_meta ani_meta">
						<div class="metafield_trigger" style="left: 10px;"><?php _e('by','shiword'); ?> <?php the_author() ?></div>
						<div class="metafield">
							<div class="metafield_trigger mft_date" style="right: 100px; width:16px"> </div>
							<div class="metafield_content">
								<?php printf(__('Published on: <b>%1$s</b>'), ''); the_time(get_option('date_format')); ?>
							</div>
						</div>
						<div class="metafield">
							<div class="metafield_trigger mft_cat" style="right: 10px; width:16px"> </div>
							<div class="metafield_content">
								<?php echo __('Categories') . ':'; ?>
								<?php the_category(', ') ?>
							</div>
						</div>
						<div class="metafield">
							<div class="metafield_trigger mft_tag" style="right: 40px; width:16px"> </div>
							<div class="metafield_content">
								<?php _e('Tags:'); ?>
								<?php if ( !get_the_tags() ) { _e('No Tags'); } else { the_tags('', ', ', ''); } ?>
							</div>
						</div>
						<div class="metafield">
							<div class="metafield_trigger mft_comm" style="right: 70px; width:16px"> </div>
							<div class="metafield_content">
								<?php _e('Comments'); ?>:
								<?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments')); // number of comments?>
							</div>
						</div>
						<div class="metafield_trigger edit_link" style="right: 130px;"><?php edit_post_link(__('Edit'),''); ?></div>
					</div>
				</div>
				<div class="storycontent">
					<?php the_excerpt(); ?>
				</div>
			</div>

			<div class="fixfloat"> </div>
		</div>
	<?php } ?>
	<div class="w_title" style="border-bottom:none;">
		<?php //num of pages
			global $paged;
			if ( !$paged ) { $paged = 1; }
			printf(__('page %1$s of %2$s','shiword'), $paged, $wp_query->max_num_pages);
		?>
	</div>
<?php } else { ?>
	<p><?php _e('Sorry, no posts matched your criteria.');?></p>
<?php } ?>
<?php get_footer(); ?>

<!-- begin sidebar -->
<div id="sidebardx">
		<div id="sidebarbody">
				<?php if ( !dynamic_sidebar( 'primary-widget-area' ) ) { ?>
					<div id="sw-search">
						<?php get_search_form(); ?>
					</div>
					<div id="w_meta" class="widget"><div class="w_title"><?php _e( 'Meta' ); ?></div>
						<ul>
							<?php wp_register(); ?>
							<li><?php wp_loginout(); ?></li>
							<?php wp_meta(); ?>
						</ul>
					</div>
					<div id="w_pages" class="widget"><div class="w_title"><?php _e( 'Pages' ); ?></div><ul><?php wp_list_pages( 'title_li=' ); ?></ul></div>
					<div id="w_bookmarks" class="widget"><div class="w_title"><?php _e( 'Blogroll' ); ?></div><ul><?php wp_list_bookmarks( 'title_li=0&categorize=0' ); ?></ul></div>
					<div id="w_categories" class="widget"><div class="w_title"><?php _e( 'Categories' ); ?></div><ul><?php wp_list_categories( 'title_li=' ); ?></ul></div>
					<div id="w_archives" class="widget"><div class="w_title"><?php _e( 'Archives' ); ?></div>
						<ul>
						<?php wp_get_archives( 'type=monthly' ); ?>
						</ul>
					</div>
				<?php } ?>
		 </div>

</div>
<!-- end sidebar -->
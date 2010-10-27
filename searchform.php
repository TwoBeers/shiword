					<div class="search-form">
						<form action="<?php echo home_url(); ?>" id="sw-searchform" method="get">
							<input type="text" onfocus="if (this.value == '<?php _e("Search") ?>...')
							{this.value = '';}" onblur="if (this.value == '')
							{this.value = '<?php _e("Search") ?>...';}" id="s" name="s" value="<?php _e('Search') ?>..." style="width: 95%;" />
							<input type="hidden" id="sw-searchsubmit" />
						</form>
					</div>

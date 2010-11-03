					<div class="search-form">
						<form action="<?php echo home_url(); ?>" class="sw-searchform" method="get">
							<input type="text" onfocus="if (this.value == '<?php _e( 'Search' ) ?>...')
							{this.value = '';}" onblur="if (this.value == '')
							{this.value = '<?php _e( 'Search' ) ?>...';}" class="sw-searchinput" name="s" value="<?php _e( 'Search' ) ?>..." style="width: 95%;" />
							<input type="hidden" class="sw-searchsubmit" />
						</form>
					</div>

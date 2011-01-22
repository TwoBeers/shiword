					<div class="search-form">
						<form action="<?php echo home_url(); ?>" class="sw-searchform" method="get">
							<input type="text" onfocus="if (this.value == '<?php _e( 'Search', 'shiword' ) ?>...')
							{this.value = '';}" onblur="if (this.value == '')
							{this.value = '<?php _e( 'Search', 'shiword' ) ?>...';}" class="sw-searchinput" name="s" value="<?php _e( 'Search', 'shiword' ) ?>..." style="width: 95%;" />
							<input type="hidden" class="sw-searchsubmit" />
						</form>
					</div>

<?php
/**
 * The gallery stuff
 *
 * @package Shiword
 * @since Shiword 3.0
 */

global $shiword_opt;

// media select
add_action( 'template_redirect', 'shiword_media' );

// check if in media preview mode
$sw_is_media = false;
if ( isset( $_GET['sw_media'] ) ) {
	$sw_is_media = true;
}

// media preview
if ( !function_exists( 'shiword_media' ) ) {
	function shiword_media () {
		global $sw_is_media;
		if ( $sw_is_media ) {
			get_template_part( 'lib/media' ); 
			exit;
		}
	}
}

// Add custom menus
add_action( 'admin_menu', 'shiword_add_gallery_menu' );


// create custom theme settings menu
if ( !function_exists( 'shiword_add_gallery_menu' ) ) {
	function shiword_add_gallery_menu() {
		$gallerypage = add_theme_page( __( 'Gallery Editor', 'shiword' ), __( 'Gallery Editor', 'shiword' ), 'edit_posts', 'tb_shiword_gallery_editor', 'shiword_edit_gallery' );
		//call custom stylesheet function
		add_action( 'admin_print_styles-' . $gallerypage, 'shiword_gallerypage_style' );
		add_action( 'admin_print_scripts-' . $gallerypage, 'shiword_gallerypage_script' );
	}
}


if ( !function_exists( 'shiword_gallerypage_style' ) ) {
	function shiword_gallerypage_style() {
		//add custom stylesheet
		wp_enqueue_style( 'thickbox' ); //shiword js
		wp_enqueue_style( 'galed-css', get_stylesheet_directory_uri() . '/css/admin-gallery.css');
	}
}

if ( !function_exists( 'shiword_gallerypage_script' ) ) {
	function shiword_gallerypage_script() {
		global $shiword_version;
		wp_enqueue_script( 'jquery' ); //shiword js
		wp_enqueue_script( 'thickbox' ); //shiword js
		wp_enqueue_script( 'galed-js', get_stylesheet_directory_uri() . '/js/admin-gallery.dev.js', array('jquery','jquery-ui-sortable'), '', false );
		//wp_enqueue_script( 'farbtastic' ); //shiword js
	}
}

// the slideshow admin panel - here you can select posts to be included in slideshow
if ( !function_exists( 'shiword_edit_gallery' ) ) {
	function shiword_edit_gallery() {
		global $shiword_current_theme;
		?>
	<div class="wrap">
			<div class="icon32" id="galed-icon"><br></div>
			<h2><?php echo $shiword_current_theme . ' - ' . __( 'Gallery Editor', 'shiword' ); ?></h2>
			<div class="hide-if-js error"><p><?php echo __( 'Javascript must be enabled in order to use this feature', 'shiword' ); ?></p></div>
			<div>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="galed-ids"><?php echo __( 'attachments', 'shiword' ); ?></label>
							</th>
							<td>
								<input type="hidden" value="" id="galed-ids" name="galed-ids">
								<?php 
									$sw_arr_params['sw_media'] = '1';
									$sw_arr_params['TB_iframe'] = 'true';
								?>
								<div id="galed-sortable-list"></div>
								<a id="galed-add-image" title="<?php echo __( 'Add Image', 'shiword' ); ?>" href="javascript:void(0)" onClick="tb_show( '<?php echo __( 'Click an image to select', 'shiword' ); ?>', '<?php echo add_query_arg( $sw_arr_params, home_url() ); ?>'); return false;">+</a>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-columns"><?php echo __( 'columns', 'shiword' ); ?></label>
							</th>
							<td>
								<select id="galed-columns" name="columns">
									<option value="0">0</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3" selected="selected">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-size"><?php echo __( 'size', 'shiword' ); ?></label>
							</th>
							<td>
								<select id="galed-size" name="size">
									<option value="thumbnail" selected="selected"><?php echo __( 'thumbnail', 'shiword' ); ?></option>
									<option value="medium"><?php echo __( 'medium', 'shiword' ); ?></option>
									<option value="large"><?php echo __( 'large', 'shiword' ); ?></option>
									<option value="full"><?php echo __( 'full', 'shiword' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-link"><?php echo __( 'link', 'shiword' ); ?></label>
							</th>
							<td>
								<select id="galed-link" name="link">
									<option value="file" selected="selected"><?php echo __( 'file', 'shiword' ); ?></option>
									<option value="attachments"><?php echo __( 'attachment', 'shiword' ); ?></option>
									<option value="none"><?php echo __( 'none', 'shiword' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-orderby"><?php echo __( 'order by', 'shiword' ); ?></label>
							</th>
							<td>
								<select id="galed-orderby" name="orderby">
									<option value="none" selected="selected"><?php echo __( 'none', 'shiword' ); ?></option>
									<option value="menu_order"><?php echo __( 'default', 'shiword' ); ?></option>
									<option value="ID">ID</option>
									<option value="author"><?php echo __( 'author', 'shiword' ); ?></option>
									<option value="date"><?php echo __( 'date', 'shiword' ); ?></option>
									<option value="comment_count"><?php echo __( 'comment count', 'shiword' ); ?></option>
									<option value="rand"><?php echo __( 'random', 'shiword' ); ?></option>
								</select>
								<small class="howto" style="display: block;"><?php echo __( 'Select "none" if you want the images to be ordered as above', 'shiword' ); ?></small>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-order"><?php echo __( 'order', 'shiword' ); ?></label>
							</th>
							<td>
								<select id="galed-order" name="order">
									<option value="ASC" selected="selected"><?php echo __( 'ascending', 'shiword' ); ?></option>
									<option value="DESC"><?php echo __( 'descending', 'shiword' ); ?></option>
									<option value="RAND"><?php echo __( 'random', 'shiword' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<input type="submit" value="<?php _e('Generate Code','shiword'); ?>" name="Submit" class="button-primary" onClick="galedGenerateCode(); return false;">
							</th>
							<td>
								<textarea id="galed-code" rows="3" readonly="readonly"></textarea>
								<small class="howto" id="galed-code-note"><?php _e('Copy this code and paste it where you want','shiword') ?></small>
							</td>
						</tr>
					</tbody>
				</table>			
			</div>
		</div>

		<?php
	}
}

?>
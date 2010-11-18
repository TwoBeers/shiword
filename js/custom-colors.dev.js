
var farbtastic;

// display the color picker
function showMeColorPicker(domid) {
	placeholder = '#shi_colorpicker_' + domid;
	jQuery(placeholder).show();
	farbtastic = jQuery.farbtastic(placeholder, function(color) { pickColor(domid,color); });
	farbtastic.setColor(jQuery('#shi_input_' + domid).val());
}

//update inputs value
function pickColor(domid,color) {
	boxid = '#shi_box_' + domid;
	inputid = '#shi_input_' + domid;
	jQuery(boxid).css('background-color', color );
	jQuery(inputid).val(color);
	updateShiPreview(domid,color);
}

//update the preview
function updateShiPreview(domid,color) {
	switch(domid)
	{
	case '1':
	  jQuery('#headimage').css('background-color', color );
	  break;
	case '2':
	  jQuery('#headimage a').css('color', color );
	  jQuery('#desc').css('color', color );
	  break;
	case '3':
	  jQuery('#preview-button').css('border-color', color );
	  break;
	case 'main3':
	  jQuery('#preview-body .preview-link').css('color', color );
	  jQuery('#preview-footer .preview-link').css('color', color );
	  jQuery('#preview-meta .preview-link').css('color', color );
	  break;
	case 'main4':
	  jQuery('#preview-body .preview-linkhi').css('color', color );
	  jQuery('#preview-footer .preview-linkhi').css('color', color );
	  jQuery('#preview-meta .preview-linkhi').css('color', color );
	  break;
	case 'menu1':
	  jQuery('#preview-menu').css('background-color', color );
	  jQuery('#preview-pages').css('background-color', color );
	  break;
	case 'menu6':
	  jQuery('#preview-menu span').css('border-color', color );
	  break;
	case 'menu3':
	  jQuery('#preview-menu .preview-text').css('color', color );
	  jQuery('#preview-pages .preview-text').css('color', color );
	  break;
	case 'menu4':
	  jQuery('#preview-menu .preview-link').css('color', color );
	  jQuery('#preview-pages .preview-link').css('color', color );
	  break;
	case 'menu5':
	  jQuery('#preview-menu .preview-linkhi').css('color', color );
	  jQuery('#preview-pages .preview-linkhi').css('color', color );
	  break;
	case 'menu2':
	  jQuery('#preview-menu').css('border-color', color );
	  jQuery('#preview-pages').css('border-color', color );
	  break;
	}
}

//toggle the two sections (outside/inside colors)
function secOpen(tableclass) {
	if ( tableclass == '.shi_bgc' ) {
		jQuery('.shi_cc').css( 'display','none' ); 
		jQuery(tableclass).toggle( 'slow' );
	}
	if ( tableclass == '.shi_cc' ) {
		jQuery('.shi_bgc').css( 'display','none' ); 
		jQuery(tableclass).toggle( 'slow' );
	}
}

//initialize
jQuery(document).ready(function() {

	jQuery('.shi_input').keyup(function() {
		var _hex = jQuery(this).val();
		var hex = _hex;
		if ( hex[0] != '#' )
			hex = '#' + hex;
		hex = hex.replace(/[^#a-fA-F0-9]+/, '');
		hex = hex.substring(0,7);
		if ( hex != _hex )
			jQuery(this).val(hex);
		if ( hex.length == 4 || hex.length == 7 )
			pickColor( jQuery(this).attr("id").replace('shi_input_', '') , hex );
		if ( hex.length == 1 )
			pickColor(  jQuery(this).attr("id").replace('shi_input_', '') , 'transparent' );
	});
	
	jQuery(document).mousedown(function(){
		jQuery('.shi_cp').each( function() {
			var display = jQuery(this).css('display');
			if (display == 'block')
				jQuery(this).fadeOut(2);
		});
	});

	jQuery('.default-device-input').click(function() {
		var cur_img = jQuery(this).parent().children('img').attr("src");
		jQuery('#headimage').css('background-image', 'url("' + cur_img + '")');
	});

	jQuery('.form-table').css( 'display','none' );
	
});

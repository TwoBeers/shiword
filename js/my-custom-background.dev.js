var farbtastic;

function pickColor(color) {
	farbtastic.setColor(color);
	jQuery('#background-color').val(color);
	jQuery('#custom-background-image').css('background-color', color);
	if ( color && color !== '#' )
		jQuery('#clearcolor').show();
	else
		jQuery('#clearcolor').hide();
}

jQuery(document).ready(function() {
	jQuery('#pickcolor').click(function() {
		jQuery('#colorPickerDiv').show();
		return false;
	});

	jQuery('#clearcolor a').click( function(e) {
		pickColor('');
		e.preventDefault();
	});

	jQuery('#background-color').keyup(function() {
		var _hex = jQuery('#background-color').val(), hex = _hex;
		if ( hex.charAt(0) != '#' )
			hex = '#' + hex;
		hex = hex.replace(/[^#a-fA-F0-9]+/, '');
		if ( hex != _hex )
			jQuery('#background-color').val(hex);
		if ( hex.length == 4 || hex.length == 7 )
			pickColor( hex );
	});

	farbtastic = jQuery.farbtastic('#colorPickerDiv', function(color) {
		pickColor(color);
	});
	pickColor(jQuery('#background-color').val());

	jQuery(document).mousedown(function(){
		jQuery('#colorPickerDiv').each(function(){
			var display = jQuery(this).css('display');
			if ( display == 'block' )
				jQuery(this).fadeOut(2);
		});
	});
	
	
	jQuery('input[name="default-bg"]').change(function() {
		jQuery('.background-details').css('display', '' );
		var bg_ref = jQuery(this).attr('value');
		var bg_url = jQuery('#default-bg-info-url-' + bg_ref).attr('value');
		var bg_color = jQuery('#default-bg-info-col-' + bg_ref).html();
		jQuery('#custom-background-image').css('background-image', 'url(' + bg_url + ')' );
		jQuery('#custom-background-image').css('background-color', bg_color );
		jQuery('#background-color').val( bg_color );
	});
});

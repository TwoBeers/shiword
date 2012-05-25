var farbtastic;
var shiwordBackground;

(function($) {

shiwordBackground = {

	//initialize
	init : function() {
		$('#pickcolor').click(function() {
			$('#colorPickerDiv').show();
			return false;
		});

		$('#clearcolor a').click( function(e) {
			shiwordBackground.pickColor('');
			e.preventDefault();
		});

		$('#background-color').keyup(function() {
			var _hex = $('#background-color').val(), hex = _hex;
			if ( hex.charAt(0) != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			if ( hex != _hex )
				$('#background-color').val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				shiwordBackground.pickColor( hex );
		});

		farbtastic = $.farbtastic('#colorPickerDiv', function(color) {
			shiwordBackground.pickColor(color);
		});
		shiwordBackground.pickColor($('#background-color').val());

		$(document).mousedown(function(){
			$('#colorPickerDiv').each(function(){
				var display = $(this).css('display');
				if ( display == 'block' )
					$(this).fadeOut(2);
			});
		});
		
		
		$('input[name="default-bg"]').change(function() {
			$('.background-details').css('display', '' );
			var bg_ref = $(this).attr('value');
			var bg_url = $('#default-bg-info-url-' + bg_ref).attr('value');
			var bg_color = $('#default-bg-info-col-' + bg_ref).html();
			$('#custom-background-image').css('background-image', 'url(' + bg_url + ')' );
			$('#custom-background-image').css('background-color', bg_color );
			$('#background-color').val( bg_color );
		});

	},

	pickColor : function (color) {
		farbtastic.setColor(color);
		$('#background-color').val(color);
		$('#custom-background-image').css('background-color', color);
		if ( color && color !== '#' )
			$('#clearcolor').show();
		else
			$('#clearcolor').hide();
	}

	
};

$(document).ready(function($){ shiwordBackground.init(); });

})(jQuery);

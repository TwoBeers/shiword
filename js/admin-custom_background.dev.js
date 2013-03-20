(function($) {
	$(document).ready(function() {
		$('input[name="default-bg"]').change(function() {
			var bg_ref = $(this).attr('value');
			var bg_url = $('#default-bg-info-url-' + bg_ref).attr('value');
			var bg_color = $('#default-bg-info-col-' + bg_ref).html();
			$('#custom-background-image').css('background-image', 'url(' + bg_url + ')' );
			$('#custom-background-image').css('background-color', bg_color );
			$('#background-color').val( bg_color );
			$('#background-color').wpColorPicker('color', bg_color)
		});
	});
})(jQuery);
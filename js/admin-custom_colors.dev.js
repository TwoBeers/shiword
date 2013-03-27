var shiwordCustomColors;


(function($) {

shiwordCustomColors = {

	//initialize
	init : function() {

		$('.shi_bgc,.shi_cc').hide();

		$('.default-device-input').click(function() {
			var cur_img = $(this).parent().children('img').attr("src");
			$('#headimage').css('background-image', 'url("' + cur_img + '")');
		});

		$('#shi_select_1').change(function(){
			var val = $(this).find('option:selected').val();
			$('#preview-button').removeClass().addClass('sw-variant-' + val);
			$('#preview-navi').removeClass().addClass('sw-variant-' + val);
		});

		var box = $('#shi_input_1a');
		var color = $('#shi_input_1');
		var preview = $('#headimage');
		
		$( "#alpha_slider" ).slider({
			orientation: "horizontal",
			value: box.val(),
			min: 0,
			max: 100,
			range: 'min',
			slide: function( event, ui ) {
				box.val( Math.round( ui.value ) );
				tmp = shiwordCustomColors.hexToRgba(color.val(), box.val() );
				preview.css('background-color', tmp );
			},
			change: function( event, ui ) {
				box.val( Math.round( ui.value ) );
				tmp = shiwordCustomColors.hexToRgba(color.val(), box.val() );
				preview.css('background-color', tmp );
			}
		});
		
		$( "#headimage" ).draggable();

		var frame;
		$('#choose-skin-from-library-link').click( function( event ) {
			var $el = $(this);

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create the media frame.
			frame = wp.media.frames.customSkin = wp.media({
				// Set the title of the modal.
				title: $el.data('choose'),

				// Tell the modal to show only images.
				library: {
					type: 'image'
				},

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: true
				}
			});

			// When an image is selected, run a callback.
			frame.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = frame.state().get('selection').first().toJSON();
				$('#custom-device-image').val(attachment.url);
				$('.default-device-input').attr('checked', false);
				$('#headimage').css('background-image','url(' + attachment.url + ')');
			});

			// Finally, open the modal.
			frame.open();
		});

		$('.color_picker').each(function() {
			$(this).wpColorPicker({
				change: function( event, ui ) {
					$(this).val( $(this).wpColorPicker('color') );
					shiwordCustomColors.updatePreview( $(this).attr('id'),ui.color.toString() );
				},
				clear: function() {
					$(this).val( 'transparent' );
					shiwordCustomColors.updatePreview( $(this).attr('id'),'transparent' );
				}
			});
		});


	},

	hexToRgba : function (hex,alpha) {
		hex = hex.replace("#", "")
		var r = shiwordCustomColors.hexToVal(hex, 0, 2);
		var g = shiwordCustomColors.hexToVal(hex, 2, 4);
		var b = shiwordCustomColors.hexToVal(hex, 4, 6);
		return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha/100 + ')';
	},
	
	hexToVal : function (h, start, end) {
		return parseInt(h.substring(start, end), 16)
	},

	//update inputs value
	pickColor : function (domid,color) {
		$('#' + domid).val(color);
		shiwordCustomColors.updatePreview(domid,color);
	},

	//update the preview
	updatePreview : function (domid,color) {
		switch(domid)
		{
		case 'shi_input_1':
		  $('#headimage').css('background-color', color );
		  $('#shi_input_1a').val(100);
		  $('#alpha_slider .handle').css('left', 205 );
		  break;
		case 'shi_input_2':
		  $('#headimage a,#desc').css('color', color );
		  break;
		case 'shi_input_3':
		  $('#preview-button').css('border-color', color );
		  break;
		case 'shi_input_main3':
		  $('#preview-body .preview-link,#preview-footer .preview-link,#preview-meta .preview-link').css('color', color );
		  break;
		case 'shi_input_main4':
		  $('#preview-body .preview-linkhi,#preview-footer .preview-linkhi,#preview-meta .preview-linkhi').css('color', color );
		  break;
		case 'shi_input_menu1':
		  $('#preview-menu,#preview-pages').css('background-color', color );
		  break;
		case 'shi_input_menu6':
		  $('#preview-menu span').css('border-color', color );
		  break;
		case 'shi_input_menu3':
		  $('#preview-menu .preview-text,#preview-pages .preview-text').css('color', color );
		  break;
		case 'shi_input_menu4':
		  $('#preview-menu .preview-link,#preview-pages .preview-link').css('color', color );
		  break;
		case 'shi_input_menu5':
		  $('#preview-menu .preview-linkhi,#preview-pages .preview-linkhi').css('color', color );
		  break;
		case 'shi_input_menu2':
		  $('#preview-menu,#preview-pages').css('border-color', color );
		  break;
		}
	},
	
	//toggle the two sections (outside/inside colors)
	secOpen : function (tableclass) {
			$(tableclass).slideToggle( 'slow' );
	}

};

$(document).ready(function($){ shiwordCustomColors.init(); });

})(jQuery);

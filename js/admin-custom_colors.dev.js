var farbtastic;
var shiwordCustomColors;


(function($) {

shiwordCustomColors = {

	//initialize
	init : function() {
		$('.shi_input').keyup(function() {
			var _hex = $(this).val();
			var hex = _hex;
			if ( hex.substr(0,1) != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			hex = hex.substring(0,7);
			if ( hex != _hex )
				$(this).val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				shiwordCustomColors.pickColor( $(this).attr("id").replace('shi_input_', '') , hex );
			if ( hex.length == 1 )
				shiwordCustomColors.pickColor(  $(this).attr("id").replace('shi_input_', '') , 'transparent' );
		});
		
		$(document).mousedown(function(){
			$('.shi_cp').each( function() {
				var display = $(this).css('display');
				if (display == 'block')
					$(this).fadeOut(2);
			});
		});

		$('.default-device-input').click(function() {
			var cur_img = $(this).parent().children('img').attr("src");
			$('#headimage').css('background-image', 'url("' + cur_img + '")');
		});

		$('.shi_bgc,.shi_cc').hide();
		
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

	// display the color picker
	colorPicker : function (domid) {
		placeholder = '#shi_colorpicker_' + domid;
		$(placeholder).show();
		farbtastic = $.farbtastic(placeholder, function(color) { shiwordCustomColors.pickColor(domid,color); });
		farbtastic.setColor($('#shi_input_' + domid).val());
	},
	
	//update inputs value
	pickColor : function (domid,color) {
		boxid = '#shi_box_' + domid;
		inputid = '#shi_input_' + domid;
		$(boxid).css('background-color', color );
		$(inputid).val(color);
		shiwordCustomColors.updatePreview(domid,color);
	},

	//update the preview
	updatePreview : function (domid,color) {
		switch(domid)
		{
		case '1':
		  $('#headimage').css('background-color', color );
		  $('#shi_input_1a').val(100);
		  $('#alpha_slider .handle').css('left', 205 );
		  break;
		case '2':
		  $('#headimage a,#desc').css('color', color );
		  break;
		case '3':
		  $('#preview-button').css('border-color', color );
		  break;
		case 'main3':
		  $('#preview-body .preview-link,#preview-footer .preview-link,#preview-meta .preview-link').css('color', color );
		  break;
		case 'main4':
		  $('#preview-body .preview-linkhi,#preview-footer .preview-linkhi,#preview-meta .preview-linkhi').css('color', color );
		  break;
		case 'menu1':
		  $('#preview-menu,#preview-pages').css('background-color', color );
		  break;
		case 'menu6':
		  $('#preview-menu span').css('border-color', color );
		  break;
		case 'menu3':
		  $('#preview-menu .preview-text,#preview-pages .preview-text').css('color', color );
		  break;
		case 'menu4':
		  $('#preview-menu .preview-link,#preview-pages .preview-link').css('color', color );
		  break;
		case 'menu5':
		  $('#preview-menu .preview-linkhi,#preview-pages .preview-linkhi').css('color', color );
		  break;
		case 'menu2':
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

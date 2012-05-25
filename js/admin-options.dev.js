var farbtastic;
var shiwordOptions;

(function($) {

shiwordOptions = {

	//initialize
	init : function() {
		shiwordOptions.switchTab('navigation');
		$('#shiword-infos').addClass ('tab-hidden');
		$('#shiword-options-li').addClass('tab-selected');
		
		$(document).mousedown(function(){
			$('.sw-colorpicker').each( function() {
				var display = $(this).css('display');
				if (display == 'block')
					$(this).fadeOut(2);
			});
		});
		
		$('#to-defaults').click (function () {
			var answer = confirm(sw_l10n.confirm_to_defaults)
			if (!answer){
				return false;
			}
		});

	},

	//update inputs value
	updateColor : function (domid,color,txtcolor) {
		inputid = '#sw-color-' + domid;
		$(inputid).css({
			'background-color' : color,
			'color' : txtcolor
		});
		$(inputid).val(color);
	},

	// display the color picker
	showColorPicker : function (domid) {
		placeholder = '#sw-colorpicker-' + domid;
		$(placeholder).show();
		farbtastic = $.farbtastic(placeholder, function(color) { 
			lightness = farbtastic.RGBToHSL(farbtastic.unpack( color ))[2];
			lightness > 0.5 ? txtcolor = '#000' : txtcolor = '#fff';
			shiwordOptions.updateColor(domid,color,txtcolor);
		});
		farbtastic.setColor($('#sw-color-' + domid).val());
	},

	switchSection : function () {
		$('#shiword-infos,#shiword-options').toggleClass('tab-hidden');
		$('#shiword-infos-li,#shiword-options-li').toggleClass('tab-selected');
	},
	
	//show only a set of rows
	switchTab : function (thisset) {
		thisclass = '.sw-tabgroup-' + thisset;
		thissel = '.sw-selgroup-' + thisset;
		$('.sw-tab-opt').css({ 'display' : 'none' });
		$(thisclass).css({ 'display' : '' });
		$('#sw-tabselector li').removeClass("sel-active");
		$(thissel).addClass("sel-active");
	}

};

$(document).ready(function($){ shiwordOptions.init(); });

})(jQuery);
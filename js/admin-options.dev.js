var shiwordOptions;

(function($) {

shiwordOptions = {

	//initialize
	init : function() {
		shiwordOptions.switchTab('navigation');
		$('#shiword-infos').addClass ('tab-hidden');
		$('#shiword-options-li').addClass('tab-selected');

		$('#shiword-options .color_picker').each(function() {
			$this = $(this);
			$this.wpColorPicker({
				change: function( event, ui ) {
					$this.val( $this.wpColorPicker('color') );
				},
				clear: function() {
					$this.val( '' );
				}
			});
		});

		$('#to-defaults').click (function () {
			var answer = confirm(sw_l10n.confirm_to_defaults)
			if (!answer){
				return false;
			}
		});

	},

	switchSection : function () {
		$('#shiword-infos,#shiword-options').toggleClass('tab-hidden');
		$('#shiword-infos-li,#shiword-options-li').toggleClass('tab-selected');
	},
	
	//show only a set of rows
	switchTab : function (thisset) {
		thisclass = '.sw-tabgroup-' + thisset;
		thissel = '#selgroup-' + thisset;
		$('.sw-tab-opt').css({ 'display' : 'none' });
		$(thisclass).css({ 'display' : '' });
		$('#sw-tabselector li').removeClass("sel-active");
		$(thissel).addClass("sel-active");
	}

};

$(document).ready(function($){ shiwordOptions.init(); });

})(jQuery);
var shiwordMovePageLink;

(function($) {

shiwordMovePageLink = {

	//initialize
	init : function() {
		
		$('#menu-appearance a[href$="tb_shiword_gallery_editor"]').parent('li').appendTo($('#menu-media ul'));

	}

};

$(document).ready(function($){ shiwordMovePageLink.init(); });

})(jQuery);
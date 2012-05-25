var shiwordSlidepage;
var shiword_rpp = 10; //rows per page
(function($) {

shiwordSlidepage = {

	init : function() {

		var n = $('.sw_post_row').length;
		var pages  = Math.ceil(n/shiword_rpp);
		var links = '';
		if ( pages > 1 ) {
			for (i = 1; i <= pages; i++)
				{
				links += '<a href="#" onclick="shiwordSlidepage.viewRowSet(' + i + ',' + n + ')">' + i + '</a>';
				}
			links += '<a href="#" onclick="shiwordSlidepage.viewAll()">all</a>';
			$('#shiwordSlide-posts').prepend('<div id="sw-paged">' + links + '</div>');
			shiwordSlidepage.viewRowSet(1);
		}

		$("#selector a").click(function() {
			shiwordSlidepage.switchClass( $(this).attr('href') );
			return false;
		});
		
		shiwordSlidepage.switchClass('#shiwordSlide-posts');

	},

	viewRowSet : function (thisset,maxnum) { //show only a set of rows
		$('.sw_post_row').css({ 'display' : 'none' });
		$('.sw_post_row').slice(((thisset-1)*shiword_rpp) , ((thisset-1)*shiword_rpp)+shiword_rpp).css({ 'display' : ''});
		$('#sw-paged a').removeClass('selected');
		$('#sw-paged a:eq(' + (thisset - 1) + ')').addClass('selected');
	},

	viewAll : function () { //show all
		$('.sw_post_row').css({ 'display' : '' });
	},

	switchClass : function (tab) { // simple animation for option tabs
		//tab = '#' + a;
		tabli = tab + '-li';
		$('.sw-slidepage-type').addClass('tab-hidden');
		$('.sw-slidepage-type-list li').removeClass('selected');
		$(tab).removeClass('tab-hidden');
		$(tabli).addClass('selected');
	}

};

$(document).ready(function($){ shiwordSlidepage.init(); });

})(jQuery);
var shiword_rpp = 10; //rows per page
jQuery(document).ready(function($){

	var n = $('.sw_post_row').length;
	var pages  = Math.ceil(n/shiword_rpp);
	var links = '';
	if ( pages > 1 ) {
		for (i = 1; i <= pages; i++)
			{
			links += '<a href="#" onclick="pagedRowView.set(' + i + ',' + n + ')">' + i + '</a>';
			}
		links += '<a href="#" onclick="pagedRowView.all()">all</a>';
		$('#shiwordSlide-posts').prepend('<div id="sw-paged">' + links + '</div>');
		pagedRowView.set(1);
	}

});

pagedRowView = {
	set : function (thisset,maxnum) { //show only a set of rows
		jQuery('.sw_post_row').css({ 'display' : 'none' });
		jQuery('.sw_post_row').slice(((thisset-1)*shiword_rpp) , ((thisset-1)*shiword_rpp)+shiword_rpp).css({ 'display' : ''});
	},
	all : function showMeAll() { //show all
		jQuery('.sw_post_row').css({ 'display' : '' });
	}
}


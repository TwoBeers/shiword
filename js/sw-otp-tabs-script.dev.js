jQuery(document).ready(function($){
	shiwordSwitchTab.set('quickbar');
});

shiwordSwitchTab = {
	set : function (thisset) { //show only a set of rows
		thisclass = '.sw-tabgroup-' + thisset;
		thissel = '.sw-selgroup-' + thisset;
		jQuery('.sw-tab-opt').css({ 'display' : 'none' });
		jQuery(thisclass).css({ 'display' : '' });
		jQuery('#sw-tabselector li').removeClass("sel-active");
		jQuery(thissel).addClass("sel-active");
	}
}

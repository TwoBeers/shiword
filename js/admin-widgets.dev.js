jQuery(document).ready(function($){
	$('#widget-list').append('<p class="clear sector-header">Shiword widgets</p>');
	sw_widgets = $('#widget-list').find('.widget[id*=_shi-]');
	$('#widget-list').append(sw_widgets);

	$('#widget-list').append('<p class="clear sector-header">bbPress widgets</p>');
	bbp_widgets = $('#widget-list').find('.widget[id*=_bbp_]');
	$('#widget-list').append(bbp_widgets);

	$('#widget-list').append('<p class="clear sector-header">BuddyPress widgets</p>');
	bp_widgets = $('#widget-list').find('.widget[id*=_bp_]');
	$('#widget-list').append(bp_widgets);
});
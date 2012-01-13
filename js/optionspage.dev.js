jQuery(document).ready(function($){
	shiwordSwitchTab.set('fixedbars');
	document.getElementById('shiword-infos').className = 'tab-hidden';
	document.getElementById('shiword-options-li').className = 'tab-selected';
	
	$(document).mousedown(function(){
		$('.sw-colorpicker').each( function() {
			var display = $(this).css('display');
			if (display == 'block')
				$(this).fadeOut(2);
		});
	});

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

function shiwordSwitchClass(a) { // simple animation for option tabs
	switch(a) {
		case 'shiword-options':
			document.getElementById('shiword-infos').className = 'tab-hidden';
			document.getElementById('shiword-options').className = '';
			document.getElementById('shiword-options-li').className = 'tab-selected';
			document.getElementById('shiword-infos-li').className = '';
		break;
		case 'shiword-infos':
			document.getElementById('shiword-infos').className = '';
			document.getElementById('shiword-options').className = 'tab-hidden';
			document.getElementById('shiword-options-li').className = '';
			document.getElementById('shiword-infos-li').className = 'tab-selected';
		break;
	}
}

var farbtastic;

// display the color picker
function shiwordShowMeColorPicker(domid) {
	placeholder = '#sw-colorpicker-' + domid;
	jQuery(placeholder).show();
	farbtastic = jQuery.farbtastic(placeholder, function(color) { shiwordPickColor(domid,color); });
	farbtastic.setColor(jQuery('#sw-color-' + domid).val());
}

//update inputs value
function shiwordPickColor(domid,color) {
	inputid = '#sw-color-' + domid;
	jQuery(inputid).css('background-color', color );
	jQuery(inputid).val(color);
}

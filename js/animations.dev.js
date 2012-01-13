jQuery(document).ready(function($){

	//main menu dropdown animation
	$('#mainmenu').children('li').each(function(){ //get every main list item
		var d = $(this).children('ul'); //for each main item, get the sub list
		if(d.size() !== 0){ //if the sub list exists...
			$(this).children('a').append('<span class="hiraquo"> »</span>'); //add a raquo to the main item
			d.css({'opacity' : 0 , 'display' : 'block'});
			var mysize = d.height(); //retrieve the height of the sub list
			d.css({'opacity' : 1 , 'display' : '', 'height': 0});
			$(this).mouseenter(function(){ //when mouse enters, slide down the sub list
				d.animate(
					{ 'height' : mysize	},
					200
				);
			}).mouseleave(function(){ //when mouse leaves, hide the sub list
				d.stop();
				d.css({'display' : '', 'height': 0});
			});
		}
	});

	//navbuttons tooltip animation
	$('#navbuttons').children('.minibutton').each( function(){ //get every minibutton
		var list = $(this).find('span.nb_tooltip');
		var trig = $(this).find('span.minib_img');
		list.css({ 'opacity' : 0, 'display' : 'block', 'min-width' : 0 });
		var mysize = list.width(); //retrieve the height of the minibutton tooltip
		list.css({ 'opacity' : 1, 'display' : '', 'width' : 0 });
		if (mysize < 200) mysize = 200;
		$(this).mouseenter( function(){ //when mouse enters, slide left the tooltip and animate the minibutton
			list.animate(
				{ 'width': mysize },
				200
			);
		}).mouseleave( function(){ //when mouse leaves, hide the tooltip
			list.stop();
			trig.stop();
			list.css({ 'opacity' : 1, 'display' : '', 'width' : 0 });
		});	
	});

	//quickbar animation
	$('#quickbar').children('.menuitem').each( function(){ //get every quickbar item
		var list = $(this).children('.menuback'); // get the sub list for each quickbar item
		var trig = $(this).children('.menuitem_img');
		list.removeClass('mi_shadowed'); //hide the box shadow (for speeding up the animation)
		list.css({ 'display' : '', 'width' : 0 });
		$(this).mouseenter( function(){ //when mouse enters, slide left the sub list, restore its shadow and animate the button
			list.animate(
				{ 'width': 832 },
				400,
				'',
				function(){ list.addClass('mi_shadowed'); }
			);
		}).mouseleave( function(){ //when mouse leaves, hide the submenu
			list.stop();
			trig.stop();
			list.removeClass('mi_shadowed');
			list.css({ 'display' : '', 'width' : 0 });
		});	
	});

	//meta animation
	$('.top_meta').children('.metafield').each( function(){  //get every metafield item
		var list = $(this).children('.metafield_content'); // get the sub list for each metafield item
		var parent = $(this).parent();
		parent.removeClass('ani_meta');
		parent.addClass('ani_meta_js');
		list.css({ 'opacity' : 0, 'display' : 'block' });
		var mysize = list.height(); //retrieve the height of the sub list
		list.css({ 'opacity' : 1, 'display' : '', 'height' : 0 , 'padding-top' : 0 });
		$(this).mouseenter( function(){ //when mouse enters, slide down the sub list
			list.animate(
				{'height': mysize , 'padding-top': 25 },
				200
			);
			parent.addClass('meta_shadowed');
			parent.css({ 'border-color' : '#cccccc' });
		}).mouseleave( function(){ //when mouse leaves, hide the sub list
			list.stop();
			list.css({ 'display' : '', 'height' : 0 , 'padding-top' : 0 });
			parent.removeClass('meta_shadowed');
			parent.css({ 'border-color' : '' });
		});
	});
	
	//add a "close" link after the submit button in minilogin form
	$('.login-submit').append( $('#closeminilogin') );
	$('#closeminilogin').css({ 'display' : 'inline' });
	$('#closeminilogin').click( function() {
		$('#sw-user_login').parents('.menuback').css({ 'display' : '' , 'width' : 0  });
		$('#user_menuback').mouseleave( function(){ //when mouse leaves, hide the submenu
			$('#sw-user_login').parents('.menuback').removeClass('mi_shadowed');
			$('#sw-user_login').parents('.menuback').css({ 'display' : '' , 'width' : 0  });
			$('#sw-user_login').parents('.cat_preview').css({ 'display' : '' });
		});
		return false;
	});
	
	//preserve the menu div from disappear when loginform name input is clicked
	$('#sw-user_login').mousedown( function() {
		$('#user_menuback').unbind("mouseleave");
		$('#sw-user_login').parents('.cat_preview').css({ 'display' : 'block' });
	});
	
	// fade in/out on scroll
	top_but = $('#navbuttons a[href="#"] span');
	bot_but = $('#navbuttons a[href="#footer"] span');
	top_but.hide();
	$(function () {
		$(window).scroll(function () {
			// check for top action
			if ($(this).scrollTop() > 100) {
				top_but.fadeIn();
			} else {
				top_but.fadeOut();
			}
			// check for bottom action
			if ( $('body').height()-$(window).scrollTop()-$(window).height() < 100) {
				bot_but.fadeOut();
			} else {
				bot_but.fadeIn();
			}

		});
	});

	// smooth scroll top/bottom
	top_but.click(function() {
		$("html, body").animate({
			scrollTop: 0
		}, {
			duration: 400
		});
		return false;
	});
	bot_but.click(function() {
		$("html, body").animate({
			scrollTop: $('#footer').offset().top - $('#head_cont').offset().top - 80
		}, {
			duration: 400
		});
		return false;
	});

	
	// widget placement
	$("#post-widgets-area .widget:nth-child(odd)").css("clear", "left");
	$("#header-widget-area .widget:nth-child(3n+1)").css("clear", "right");
	$("#error404-widgets-area .widget:nth-child(odd)").css("clear", "left");
	
	//thickbox init
	$('.storycontent a img').parent('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]').addClass('thickbox');
	$('.storycontent .gallery').each(function() {
		$('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]',$(this)).attr('rel', $(this).attr('id'));
	});

});
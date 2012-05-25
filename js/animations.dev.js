/**
 * The animations
 *
 */

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
				d.stop().css({'display' : '', 'height': 0});
			});
		}
	});

	//navbuttons tooltip animation
	$('#navbuttons').children('.minibutton').each( function(){ //get every minibutton
		var list = $(this).find('span.nb_tooltip');
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
			list.css({ 'opacity' : 1, 'display' : '', 'width' : 0 });
		});	
	});

	//quickbar animation
	$('#quickbar').children('.menuitem').each( function(){ //get every quickbar item
		var list = $(this).children('.menuback'); // get the sub list for each quickbar item
		list.css({ 'display' : '', 'width' : 0 }).removeClass('mi_shadowed'); //hide the box shadow (for speeding up the animation)
		$(this).mouseenter( function(){ //when mouse enters, slide left the sub list, restore its shadow and animate the button
			if ( $('#sw-user_login').hasClass('keepme') ) return;
			list.animate(
				{ 'width': 832 },
				400,
				'',
				function(){ list.addClass('mi_shadowed'); }
			);
		}).mouseleave( function(){ //when mouse leaves, hide the submenu
			if ( $('#sw-user_login').hasClass('keepme') ) return;
			list.stop();
			list.removeClass('mi_shadowed').css({ 'display' : '', 'width' : 0 });
		});	
	});

	//meta animation
	$('.top_meta').removeClass('ani_meta').addClass('ani_meta_js').children('.metafield').each( function(){  //get every metafield item
		var list = $(this).children('.metafield_content'); // get the sub list for each metafield item
		var parent = $(this).parent();
		list.css({ 'opacity' : 0, 'display' : 'block' });
		var mysize = list.height(); //retrieve the height of the sub list
		list.css({ 'opacity' : 1, 'display' : '', 'height' : 0 , 'padding-top' : 0 });
		$(this).mouseenter( function(){ //when mouse enters, slide down the sub list
			list.animate(
				{'height': mysize , 'padding-top': 25 },
				200
			);
			parent.addClass('meta_shadowed').css({ 'border-color' : '#555' });
		}).mouseleave( function(){ //when mouse leaves, hide the sub list
			list.stop().css({ 'display' : '', 'height' : 0 , 'padding-top' : 0 });
			parent.removeClass('meta_shadowed').css({ 'border-color' : '' });
		});
	});
	
	//add a "close" link after the submit button in minilogin form
	$('.login-submit').append( $('#closeminilogin') );
	$('#closeminilogin').css({ 'display' : 'inline' });
	$('#closeminilogin').click( function() {
		$('.menuitem_img').show();
		$('#user_menuback .menuback').css({ 'display' : '' , 'width' : 0  });
		$('#sw_minilogin_wrap').css({ 'display' : '' });
		$('#sw-user_login').removeClass('keepme');
		return false;
	});
	
	//preserve the menu div from disappear when loginform name input is clicked
	$('#sw-user_login').mousedown( function() {
		$('#sw_minilogin_wrap').css({ 'display' : 'block' });
		$('#user_menuback .menuback').css({ 'display' : 'block' });
		$(this).addClass('keepme');
		$('.menuitem_img').fadeOut();
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
			scrollTop: $('#footer').offset().top - 80
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

/**
 * The post slider
 *
 */

(function($) {
	
	$.fn.sw_sticky_slider = function(options) {

		// set default options
		var defaults = {
			speed : 1000, //duration of the animation
			pause : 2000 //pause between animations
		},

		// Take the options that the user selects, and merge them with defaults.
		options = $.extend(defaults, options);
		
		// for each item in the wrapped set
		return this.each(function() {
		
			// cache "this."
			var $this = $(this);
			
			// Set the width to a really high number. Adjusting the "left" css values, so need to set positioning.
			$this.css({
				'width' : '50000px',
				'position' : 'relative',
				'display' : 'block'
			});
			// initialize
			$this.children().css({
				'float' : 'left',
				'list-style' : 'none',
				'height' : $this.parent().css('height'),
				'width' : $this.parent().css('width')
			});
			if ($this.children().size() > 1) {
			
				// call the slide function.
				timId = timed_slide();
				
				//react to mouse event
				$this.parent().mouseenter(function(){ //when mouse enters the slider
					clearInterval(timId);
				}).mouseleave(function(){  //when mouse leaves the slider
					timId = timed_slide();
				});
				
				$('.sw_slider-skip.toright',$this.parent()).click(function(){
					slide();
				})
				
				$('.sw_slider-skip.toleft',$this.parent()).click(function(){
					slide('toleft');
				})
				
			}
			function timed_slide() {
				timId = setInterval(function() {
					slide();
				}, (options.speed + options.pause));
				return timId;
			} // end timed_slide
			function slide( direction ) {
				if ( direction == 'toleft' ) {
					// Animate to the right the width of the image/div
						if( ! $this.is(':animated') ) {
							$this
								.css('left', '-' + $this.parent().width() + 'px')
								.children(':last')
								.prependTo($this);
						}
						$this.stop().animate({'left' : 0}, options.speed);
				} else {
					// Animate to the left the width of the image/div
					$this.stop().animate({'left' : '-' + $this.parent().width()}, options.speed, function() {
						// Return the "left" CSS back to 0, and append the first child to the very end of the list.
						$this
						   .css('left', 0)
						   .children(':first')
						   .appendTo($this); // move it to the end of the line.
					})
				}
			} // end slide
		}); // end each
	} // End plugin.
	
})(jQuery);
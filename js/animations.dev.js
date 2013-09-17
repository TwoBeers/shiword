/**
 * The animations
 *
 */

var shiwordAnimations;

(function($) {

shiwordAnimations = {

	//initialize
	init : function( in_modules ) {

		var modules = in_modules.split(',');

		for (i in modules) {

			switch(modules[i]) {

				case 'main_menu':
					this.main_menu();
					break;

				case 'navigation_buttons':
					this.navigation_buttons();
					break;

				case 'smooth_scroll':
					this.smooth_scroll();
					break;

				case 'quickbar_panels':
					this.quickbar_panels();
					break;

				case 'minilogin':
					this.minilogin();
					break;

				case 'entry_meta':
					this.entry_meta();
					break;

				case 'widgets_style':
					this.widgets_style();
					break;

				case 'post_expander':
					this.post_expander();
					break;

				case 'thickbox':
					this.thickbox();
					break;

				case 'quote_this':
					this.quote_this();
					break;

				case 'slider':
					this.slider();
					break;

				case 'resize_video':
					this.resize_video();
					break;

				case 'tinynav':
					this.tinynav();
					break;

				default :
					//no default action
					break;

			}

		}

	},

	main_menu : function() {

		//main menu dropdown animation
		$('#mainmenu').children('.menu-item-parent').each(function(){ //get every main list item
			var $this = $(this);
			var d = $this.children('ul'); //for each main item, get the sub list
			d.css({'display' : 'block'}).hide();
			$this.hoverIntent(
				function(){ //when mouse enters, slide down the sub list
					d.slideDown(200);
				},
				function(){ //when mouse leaves, hide the sub list
					d.hide();
				}
			);
		});

	},

	navigation_buttons : function() {

		//navbuttons tooltip animation
		$('#navbuttons').children('.minibutton').each( function(){ //get every minibutton
			var $this = $(this);
			var label = $this.find('span.nb_tooltip');
			label.css({ 'opacity' : 0, 'display' : 'block' });
			var mysize = label.width(); //retrieve the height of the minibutton tooltip
			label.css({ 'opacity' : 1, 'width' : 0, 'min-width' : 0 }).hide();
			$this.hoverIntent( 
				function(){ //when mouse enters, slide left the tooltip and animate the minibutton
					label.show().animate({ 'width': mysize }, 200);
				},
				function(){ //when mouse leaves, hide the tooltip
					label.stop().css({ 'width' : 0 }).hide();
				}
			);
		});

	},

	smooth_scroll : function() {

		// fade in/out on scroll
		top_but = $('#navbuttons').find('a[href="#"] span');
		bot_but = $('#navbuttons').find('a[href="#footer"] span');
		top_fade = $('#top_fade');
		bottom_fade = $('#bottom_fade');
		top_but.hide();
		bottom_fade.show();
		$(function () {
			$(window).scroll(function () {
				// check for top action
				if ($(this).scrollTop() > 100) {
					top_but.slideDown();
					top_fade.slideDown();
				} else {
					top_but.slideUp();
					top_fade.slideUp();
				}
				// check for bottom action
				if ( $('body').height()-$(window).scrollTop()-$(window).height() < 100) {
					bot_but.slideUp();
					bottom_fade.slideUp();
				} else {
					bot_but.slideDown();
					bottom_fade.slideDown();
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

	},

	quickbar_panels : function() {

		//quickbar animation
		$('#quickbar').children('.menuitem').each( function(){ //get every quickbar item
			var $this = $(this);
			var panel = $this.children('.menuback'); // get the sub panel for each quickbar item
			panel.css({ 'display' : 'block', 'width' : 0 }).removeClass('mi_shadowed').hide(); //hide the box shadow (for speeding up the animation)
			$this.hoverIntent(
				function(){ //when mouse enters, slide left the sub panel, restore its shadow and animate the button
					if ( $('#sw-user_login').hasClass('keepme') ) return;
					panel.show().animate({ 'width': 832 },400,'',function(){ panel.addClass('mi_shadowed'); });
				},
				function(){ //when mouse leaves, hide the submenu
					if ( $('#sw-user_login').hasClass('keepme') ) return;
					panel.stop().css({ 'width' : 0 }).removeClass('mi_shadowed').hide();
				}
			);
		});

	},

	minilogin : function() {

		//add a "close" link after the submit button in minilogin form
		$closeminilogin = $('#closeminilogin');
		$('.login-submit').append( $closeminilogin );
		$closeminilogin.css({ 'display' : 'inline' });
		$closeminilogin.click( function() {
			$('.menuitem_img').show();
			$('#user_menuback').find('.menuback').css({ 'display' : '' , 'width' : 0  });
			$('#sw_minilogin_wrap').css({ 'display' : '' });
			$('#sw-user_login').removeClass('keepme');
			return false;
		});
		
		//preserve the menu div from disappear when loginform name input is clicked
		$('#sw-user_login').mousedown( function() {
			$('#sw_minilogin_wrap').css({ 'display' : 'block' });
			$('#user_menuback').find('.menuback').css({ 'display' : 'block' });
			$(this).addClass('keepme');
			$('.menuitem_img').fadeOut();
		});

	},

	entry_meta : function() {

		$('body').on('post-load', function(event){
			shiwordAnimations.entry_meta_apply();
		});
		this.entry_meta_apply();

	},

	entry_meta_apply : function() {

		//meta animation
		$('#maincontent').find('.top_meta.ani_meta').removeClass('ani_meta').addClass('ani_meta_js').children('.metafield').each( function(){  //get every metafield item
			var $this = $(this);
			var list = $this.children('.metafield_content'); // get the sub list for each metafield item
			var parent = $this.parent();
			list.css({ 'display' : 'block' }).hide();
			$this.hoverIntent(
				function(){ //when mouse enters, slide down the sub list
					list.slideDown(200);
					parent.addClass('meta_shadowed');
				},
				function(){ //when mouse leaves, hide the sub list
					list.hide();
					parent.removeClass('meta_shadowed');
				}
			);
		});

	},

	widgets_style : function() {

		// widget placement
		$("#post-widgets-area").find(".widget:nth-child(odd)").css("clear", "left");
		$("#header-widget-area").find(".widget:nth-child(3n+1)").css("clear", "right");
		$("#error404-widgets-area").find(".widget:nth-child(odd)").css("clear", "left");

	},

	thickbox : function() {

		//thickbox init
		$('#posts-container').find('.storycontent a img').parent('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]').addClass('thickbox');
		$('#posts-container').find('.storycontent .gallery').each(function() {
			var $this = $(this);
			$('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]',$this).attr('rel', $this.attr('id'));
		});

	},

	quote_this : function () {
		htmltext = '<span> - </span><a id="tb-quotethis" href="#" onclick="shiwordAnimations.add_quote(); return false" title="' + shiword_l10n.quote_link_info + '" >' + shiword_l10n.quote_link_text + '</a>'
		$(htmltext).appendTo('#reply-title');
	},

	add_quote : function() {

		var posttext = '';
		if (window.getSelection){
			posttext = window.getSelection();
		}
		else if (document.getSelection){
			posttext = document.getSelection();
		}
		else if (document.selection){
			posttext = document.selection.createRange().text;
		}
		else {
			return true;
		}
		posttext = posttext.toString().replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
		if ( posttext.length !== 0 ) {
			document.getElementById("comment").value = document.getElementById("comment").value + '<blockquote>' + posttext + '</blockquote>';
		} else {
			alert(shiword_l10n.quote_link_alert);
		}

	},

	//show only a set of rows
	post_expander : function () {
		$('#posts-container').find('a.more-link').click(function() {
			var link = $(this);

			$.ajax({
				type: 'POST',
				url: link.attr("href"),
				beforeSend: function(XMLHttpRequest) { link.html(shiword_l10n.post_expander_wait).addClass('ajaxed'); },
				data: 'shiword_post_expander=1',
				success: function(data) { link.parents(".storycontent").hide().html($(data)).fadeIn(600); }
			});	

			return false;

		});
	},

	slider : function () {
		$('#sw_sticky_slider').sw_sticky_slider({
			speed : parseInt(shiword_l10n.slider_speed),
			pause : parseInt(shiword_l10n.slider_pause)
		})
	},

	resize_video : function() {
		// https://github.com/chriscoyier/Fluid-Width-Video
		var $fluidEl = $("#maincontent").find(".storycontent");
		var $allVideos = $("iframe[src^='http://player.vimeo.com'], iframe[src^='http://www.youtube.com'], object, embed",$fluidEl);

		$allVideos.each(function() {
			$(this)
				// jQuery .data does not work on object/embed elements
				.attr('data-aspectRatio', this.height / this.width)
				.removeAttr('height')
				.removeAttr('width');
		});

		$(window).resize(function() {
			var newWidth = $fluidEl.width();
			$allVideos.each(function() {
				var $el = $(this);
				$el
					.width(newWidth)
					.height(newWidth * $el.attr('data-aspectRatio'));
			});
		}).resize();
	},

	tinynav : function() {
		if ( $(window).width() > 800 ) return false;
		$(".nav-menu").tinyNav({
			active: 'current_page_item', // Set the "active" class for default menu
			label: '', // String: Sets the <label> text for the <select> (if not set, no label will be added)
			header: '' // String: Specify text for "header" and show header instead of the active item
		});
	}

};

$(document).ready(function($){ shiwordAnimations.init(shiword_l10n.script_modules); });

})(jQuery);

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

			var ready_to_slide = true;

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
				$this.parent().hover(
					function(){ //when mouse enters the slider
						clearInterval(timId);
					},
					function(){  //when mouse leaves the slider
						timId = timed_slide();
					}
				);

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
				if( $this.is(':animated') ) return;
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
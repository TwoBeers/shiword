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
			d.css({'opacity' : 0 , 'display' : 'block'});
			var mysize = d.height(); //retrieve the height of the sub list
			d.css({'opacity' : 1 , 'display' : '', 'height': 0});
			$this.mouseenter(function(){ //when mouse enters, slide down the sub list
				d.animate(
					{ 'height' : mysize	},
					200
				);
			}).mouseleave(function(){ //when mouse leaves, hide the sub list
				d.stop().css({'display' : '', 'height': 0});
			});
		});

	},

	navigation_buttons : function() {

		//navbuttons tooltip animation
		$('#navbuttons').children('.minibutton').each( function(){ //get every minibutton
			var $this = $(this);
			var list = $this.find('span.nb_tooltip');
			list.css({ 'opacity' : 0, 'display' : 'block', 'min-width' : 0 });
			var mysize = list.width(); //retrieve the height of the minibutton tooltip
			list.css({ 'opacity' : 1, 'display' : '', 'width' : 0 });
			if (mysize < 200) mysize = 200;
			$this.mouseenter( function(){ //when mouse enters, slide left the tooltip and animate the minibutton
				list.animate(
					{ 'width': mysize },
					200
				);
			}).mouseleave( function(){ //when mouse leaves, hide the tooltip
				list.stop();
				list.css({ 'opacity' : 1, 'display' : '', 'width' : 0 });
			});	
		});

		// fade in/out on scroll
		top_but = $('#navbuttons').find('a[href="#"] span');
		bot_but = $('#navbuttons').find('a[href="#footer"] span');
		top_but.hide();
		$(function () {
			$(window).scroll(function () {
				// check for top action
				if ($(this).scrollTop() > 100) {
					top_but.slideDown();
				} else {
					top_but.slideUp();
				}
				// check for bottom action
				if ( $('body').height()-$(window).scrollTop()-$(window).height() < 100) {
					bot_but.slideUp();
				} else {
					bot_but.slideDown();
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
			var list = $this.children('.menuback'); // get the sub list for each quickbar item
			list.css({ 'display' : '', 'width' : 0 }).removeClass('mi_shadowed'); //hide the box shadow (for speeding up the animation)
			$this.mouseenter( function(){ //when mouse enters, slide left the sub list, restore its shadow and animate the button
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
		$('.top_meta.ani_meta').removeClass('ani_meta').addClass('ani_meta_js').children('.metafield').each( function(){  //get every metafield item
			var $this = $(this);
			var list = $this.children('.metafield_content'); // get the sub list for each metafield item
			var parent = $this.parent();
			list.css({ 'opacity' : 0, 'display' : 'block' });
			var mysize = list.height(); //retrieve the height of the sub list
			list.css({ 'opacity' : 1, 'display' : '', 'height' : 0 , 'padding-top' : 0 });
			$this.mouseenter( function(){ //when mouse enters, slide down the sub list
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
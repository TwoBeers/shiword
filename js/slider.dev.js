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
				timId = slide();
				
				//react to mouse event
				$this.mouseenter(function(){ //when mouse enters the slider
					clearInterval(timId);
				}).mouseleave(function(){  //when mouse leaves the slider
					timId = slide();
				});
				
			}
			function slide() {
				timId = setInterval(function() {
					// Animate to the left the width of the image/div
					$this.animate({'left' : '-' + $this.parent().width()}, options.speed, function() {
						// Return the "left" CSS back to 0, and append the first child to the very end of the list.
						$this
						   .css('left', 0)
						   .children(':first')
						   .appendTo($this); // move it to the end of the line.
					})
				}, (options.speed + options.pause));
				return timId;
			} // end slide
		}); // end each
	} // End plugin.
	
})(jQuery);
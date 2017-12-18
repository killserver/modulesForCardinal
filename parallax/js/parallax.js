(function($) {

	$.fn.parallaxCardinal = function(options) {

		var settings = $.extend({
			'speed' : 0.15,
			'isMobile' : true,
			'detectMobile' : true,
			'image' : '',
			'size' : 'cover',
			'position' : '50%',
			'zIndex' : '-5',
			'persentPlus' : '5vh',
		}, options);

	    var isOnScreen = function(elemTh) {
	    	elemTh = jQuery(elemTh);
			var viewport = {};
			viewport.top = jQuery(window).scrollTop();
			viewport.bottom = viewport.top + jQuery(window).height();
			var bounds = {};
			bounds.top = elemTh.offset().top;
			bounds.bottom = bounds.top + elemTh.outerHeight();
			return bounds.top <= viewport.bottom && bounds.bottom >= viewport.top;
		};

		jQuery(document).scroll(function(e) {
			jQuery('.parallax-wrapper').each(function(index, el) {
				var elOffset = jQuery(el).parent().offset().top;
				var winTop = jQuery(window).scrollTop();
				var scrll = (winTop - elOffset) * settings.speed;
				if(isOnScreen(el)) {
					jQuery(el).css('transform', 'translate3d(0, ' + scrll + 'px, 0)');
				}
			});
		});

	    return this.each(function(i, elem) {
			var image;
			if(settings.image.length==0) {
				image = jQuery(elem).attr('parallax-background');
				if(typeof(image) === "undefined" || image.length==0) {
					image = jQuery(elem).css("backgroundImage");
					image = image.substr(5);
					image = image.substr(0, image.length-2);
				}
			} else {
				image = settings.image;
			}
			if(typeof(image) === "undefined" || image.length==0) {
				console.error("Image is not set");
				return false;
			}
			var position = settings.position;
			if(typeof(position)!=="undefined" && position.length>0) {
				if(position.trim().indexOf(" ")==-1) {
					position = position+" "+position;
				}
			} else {
				console.error("Position is not set");
				return false;
			}
			var sets = {};
			sets['backgroundImage'] = "none";
			if(jQuery(elem).css("position")!="fixed" && jQuery(elem).css("position")!="absolute" && jQuery(elem).css("position")!="relative") {
				sets['position'] = "relative";
			}
			jQuery(elem).css(sets);
			if(!settings.isMobile || (settings.detectMobile && $(window).width()>900)) {
				jQuery(elem).append('<div class="parallax-container" style="position: absolute; width:100%; height:100%; top:0; left: 0; right: 0; bottom: 0; overflow: hidden; z-index: '+settings.zIndex+';"><div class="parallax-wrapper" style="position: absolute; top: 0; left: 0; width: 100%; height: 105%; height: -webkit-calc(100% + '+settings.persentPlus+'); height: -moz-calc(100% + '+settings.persentPlus+'); height: calc(100% + '+settings.persentPlus+');"><div class="parallax-bg" style="background-image: url(' + image + '); background-size: '+settings.size+'; background-repeat: no-repeat; background-position: '+position+'; position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%;"></div></div></div>');
			} else {
				jQuery(elem).append('<div class="parallax-container" style="position: absolute; width:100%; height:100%; top:0; left: 0; right: 0; bottom: 0; overflow: hidden; z-index: '+settings.zIndex+';"><div class="parallax-wrapper" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"><div class="parallax-bg" style="background-image: url(' + image + '); background-size: '+settings.size+'; background-repeat: no-repeat; background-position: '+position+'; position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%;"></div></div></div>');
			}
		});

	};
})(jQuery);

if(typeof(parallaxAutoInit)!=="undefined") {
	jQuery(document).ready(function($) {
		$('.parallax-image').parallaxCardinal();
	});
}
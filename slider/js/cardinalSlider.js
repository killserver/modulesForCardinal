(function($) {

    var lists = [];

    var methods = {
        init : function(options) {
            var settings = $.extend({
                'slidePerView' : 1,
            }, options);
            this.each(function(i, elem) {
                var slideWidth = (jQuery(elem).innerWidth() / settings.slidePerView - (typeof(settings.slideMargin)!=="undefined" && settings.slideMargin>0 ? settings.slideMargin / 2 : 0));
                var ch = jQuery(elem).children();
                if(typeof(ch[0])!=="undefined") {
                    var marginLeft = parseFloat(jQuery(ch[0]).css("marginLeft"));
                    var marginRight = parseFloat(jQuery(ch[0]).css("marginRight"));
                } else {
                    var marginLeft = 0;
                    var marginRight = 0;
                }
                if(typeof(settings.slideMargin)==="undefined") {
                    settings.slideMargin = (marginLeft+marginRight);
                }
                console.log(settings);
                slideWidth = slideWidth-(marginLeft/1.5)-(marginRight/1.5);
                var subSettings = $.extend({'slideWidth': slideWidth, 'minSlides' : settings.slidePerView, 'maxSlides' : settings.slidePerView}, settings);
                lists[lists.length] = jQuery(elem).bxSlider(subSettings);
            });
            return lists;
        },
        goToSlide : function(id) {
            for(var i=0;i<lists.length;i++) {
                lists[i].goToSlide(id);
            }
            return true;
        },
        goToNextSlide : function() {
            for(var i=0;i<lists.length;i++) {
                lists[i].goToNextSlide();
            }
            return true;
        },
        goToPrevSlide : function() {
            for(var i=0;i<lists.length;i++) {
                lists[i].goToPrevSlide();
            }
            return true;
        },
        startAuto : function() {
            for(var i=0;i<lists.length;i++) {
                lists[i].startAuto();
            }
            return true;
        },
        stopAuto : function() {
            for(var i=0;i<lists.length;i++) {
                lists[i].stopAuto();
            }
            return true;
        },
        getCurrentSlide : function() {
            var arr = [];
            for(var i=0;i<lists.length;i++) {
                arr[arr.length] = lists[i].getCurrentSlide();
            }
            return arr;
        },
        getSlideCount : function() {
            var arr = [];
            for(var i=0;i<lists.length;i++) {
                arr[arr.length] = lists[i].getSlideCount();
            }
            return arr;
        },
        redrawSlider : function() {
            for(var i=0;i<lists.length;i++) {
                lists[i].redrawSlider();
            }
            return true;
        },
        reloadSlider : function() {
            for(var i=0;i<lists.length;i++) {
                lists[i].reloadSlider();
            }
            return true;
        },
        destroySlider : function() {
            for(var i=0;i<lists.length;i++) {
                lists[i].destroySlider();
            }
            return true;
        }
    };

    $.fn.cardinalBxSlider = function(method) {
        if(typeof(methods[method])!=="undefined") {
            jQuery(window).resize(function() {
                jQuery(this).children().each(function(i, elem) {
                    jQuery(elem).css("width", jQuery(window).outerWidth());
                });
                jQuery(this).cardinalBxSlider("redrawSlider");
            });
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if(typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Метод с именем '+method+' не существует для jQuery.cardinalBxSlider');
        }
    }
})(jQuery);
(function($) {

    $.fn.cardinalGoogleMaps = function(options) {

        var elem = jQuery(this);

        var settings = $.extend({
            'zoom' : 12,
            'gestureHandling': 'auto',
            'fullscreenControl': false,
            'zoomControl': false,
            'disableDoubleClickZoom': true,
            'mapTypeControl': false,
            'scaleControl': false,
            'scrollwheel': true,
            'streetViewControl': false,
            'draggable': true,
            'clickableIcons': false,
            'mapTypeId': google.maps.MapTypeId.ROADMAP,
            'styles': [{"featureType": "all","elementType": "all","stylers":[{"saturation":"-94"},{"invert_lightness":true},{"lightness":"10"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":10}]},{"featureType":"road.highway","elementType":"all","stylers":[{"color":"#b29467"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"hue":"#ff0000"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"on"}]}],
            'icon': '',
            'title': '',
            'address': (typeof(addressMapForCardinal)!=="undefined" ? addressMapForCardinal.trim() : ''),
            'key': 'AIzaSyChZ1lL58ijesJoIPgkd-KsovKezdMa8N0',
        }, options);

        if(settings.address=="") {
            console.error("Address is not set");
            return false;
        }
        if(settings.key=="") {
            console.error("Key is not set");
            return false;
        }

        function getLocation() {
            elem.each(function(i, th) {
                jQuery.post('https://maps.googleapis.com/maps/api/geocode/json?address='+encodeURIComponent(settings.address)+'&key='+settings.key, function(data) {}).done(function(data) {
                    var res = data;
                    var locSettings = settings;
                    delete settings['address'];
                    delete settings['key'];
                    delete settings['icon'];
                    var mapOptions = $.extend(settings, {
                        center: new google.maps.LatLng(res.results[0].geometry.location.lat, res.results[0].geometry.location.lng)
                    });
                    var map = new google.maps.Map(th, mapOptions);
                    var marker = new google.maps.Marker({
                        icon: locSettings.icon,
                        position: new google.maps.LatLng(res.results[0].geometry.location.lat, res.results[0].geometry.location.lng),
                        map: map
                    });
                }).fail(function(data) {
                    console.error("-= Error parsing Google Maps for Cardinal Engine =-");
                    console.error(data);
                });
            });
        }
        google.maps.event.addDomListener(window, 'load', getLocation);
    };
})(jQuery);
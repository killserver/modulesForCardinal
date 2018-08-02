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
            'lat': '',
            'lng': '',
            'markerLat': 0,
            'markerLng': 0,
            'address': (typeof(addressMapForCardinal)!=="undefined" ? addressMapForCardinal.trim() : ''),
            'key': 'AIzaSyChZ1lL58ijesJoIPgkd-KsovKezdMa8N0',
        }, options);

        if(settings.lat==""&&settings.lng==""&&settings.address=="") {
            console.error("Address is not set");
            return false;
        }
        if(settings.key=="") {
            console.error("Key is not set");
            return false;
        }

        function getLocation() {
            elem.each(function(i, th) {
                if(settings.address!=="") {
                    jQuery.post('https://maps.googleapis.com/maps/api/geocode/json?address='+encodeURIComponent(settings.address)+'&key='+settings.key, function(data) {}).done(function(data) {
                        var res = data;
                        var locSettings = settings;
                        var markerLat = locSettings.markerLat;
                        var markerLng = locSettings.markerLng;
                        delete locSettings['lat'];
                        delete locSettings['lng'];
                        delete locSettings['markerLat'];
                        delete locSettings['markerLng'];
                        delete locSettings['address'];
                        delete locSettings['key'];
                        delete locSettings['icon'];
                        var mapOptions = $.extend(locSettings, {
                            center: new google.maps.LatLng(res.results[0].geometry.location.lat, res.results[0].geometry.location.lng)
                        });
                        var map = new google.maps.Map(th, mapOptions);
                        var marker = new google.maps.Marker({
                            icon: locSettings.icon,
                            position: new google.maps.LatLng(res.results[0].geometry.location.lat+markerLat, res.results[0].geometry.location.lng+markerLng),
                            map: map
                        });
                    }).fail(function(data) {
                        console.error("-= Error parsing Google Maps for Cardinal Engine =-");
                        console.error(data);
                    });
                } else if(settings.lat!=="" && settings.lng!=="") {
                    var locSettings = settings;
                    var markerLat = locSettings.markerLat;
                    var markerLng = locSettings.markerLng;
                    delete locSettings['markerLat'];
                    delete locSettings['markerLng'];
                    delete locSettings['address'];
                    delete locSettings['key'];
                    delete locSettings['icon'];
                    var mapOptions = $.extend(locSettings, {
                        center: new google.maps.LatLng(locSettings.lat, locSettings.lng)
                    });
                    var map = new google.maps.Map(th, mapOptions);
                    var marker = new google.maps.Marker({
                        icon: locSettings.icon,
                        position: new google.maps.LatLng(locSettings.lat+markerLat, locSettings.lng+markerLng),
                        map: map
                    });
                }
            });
        }
        google.maps.event.addDomListener(window, 'load', getLocation);
    };
})(jQuery);
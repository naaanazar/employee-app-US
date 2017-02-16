'use strict';

var Map;

define(['https://maps.googleapis.com/maps/api/js?key=AIzaSyDMgnsp7HMAHLR_ntjubgpnt3A8evQvsgg&libraries=geometry'], function() {

    jQuery('document').ready(function () {

        /**
         * dashboard/search
         * init map
         */
        if (jQuery('div').is('#map-container')) {
            Address.start();
            Address.clickOnMap();
        };

        /**
         * employe/index
         * init map & create marker on map & change coordinates & address ( click on button )
         */
        jQuery(document).on('click', '.update-marker', function (event) {
            event.defaultPrevented = true;

            if (!jQuery('div').is('.init')) {
                jQuery('#init-map').css('display', 'block');
                Address.start();
                jQuery('#map').addClass('init');
            };

            Address.clickOnMap();
            Address.findAddress(Address.map);

            return false;
        });

        jQuery(document).on('click', '.hide-map', function (event) {
            event.preventDefault();

            if (jQuery('div').is('.init')) {
                jQuery('#init-map').css('display', 'none');
                jQuery('#map').removeClass('init');

            }
            ;
        });
    });

    Map = {
        images: '/img/marker.png',
        markers: [],

        init: function () {
            return new google.maps.Map(document.getElementById('map'), {
                center: {lat: 50.98609893339354, lng: 10.39306640625},
                zoom: 6
            });
        },
        addMarker: function (map, lat, lng) {
            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map,
                icon: this.images
            });
            Map.markers.push(marker);
            return marker;
        },
        clearMarker: function () {
            if (Map.markers.length > 0) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
            }
        },
        infoWindows: function (content) {
            return new google.maps.InfoWindow({
                content: content
            });
        },
        centeringMap: function (points, map) {

            var latlngbounds = new google.maps.LatLngBounds();
            for (var i in points) {
                latlngbounds.extend(points[i]);
            }
            map.setCenter(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
        }
    };

    /**
     *
     * @type {{fullAddress: string, marker: null, maps: null, finfCoords: Function, findAddress: Function, setAddress: Function}}
     */
    var Address = {
        mustache: null,
        fullAddress: '',
        marker: null,
        map: null,

        start: function () {
            Address.map = Map.init();
        },

        /**
         * find the coordinates of the address
         * @param map
         * @param event
         */
        finfCoords: function (map, event) {

            if (null != this.marker) {
                this.marker.setMap(null);
            }

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'location': {
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()
                }
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    var address = results[0].formatted_address.split(',');
                    var city = address[1].trim().split(' ');

                    if (jQuery('input').is('#city_field')) {
                        document.getElementById('city_field').value = city[1];
                    };

                    if (jQuery('input').is('#address_field')) {
                        document.getElementById('address_field').value = address[0].trim();
                    };

                    if (jQuery('input').is('#address_field')) {
                        document.getElementById('zip_field').value = city[0];
                    };

                }
            });

            this.marker = Map.addMarker(map, event.latLng.lat(), event.latLng.lng());
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        },

        /**
         * find the address of the coordinates
         * @param map
         */
        findAddress: function (map) {

            if (null != this.marker) {
                this.marker.setMap(null);
            }

            this.setAddress();

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': this.fullAddress}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(16);

                    Address.marker = Map.addMarker(map, results[0].geometry.location.lat(), results[0].geometry.location.lng());

                    document.getElementById('latitude').value = results[0].geometry.location.lat();
                    document.getElementById('longitude').value = results[0].geometry.location.lng();
                }
            });
        },

        /**
         * update full address for requests place
         */
        setAddress: function () {

            this.fullAddress = 'Germany,';

            if ('' != jQuery('#zip_field').val()) {
                this.fullAddress = this.fullAddress + jQuery('#zip_field').val() + ',';
            }
            if ('' != jQuery('#city_field').val()) {
                this.fullAddress = this.fullAddress + jQuery('#city_field').val() + ',';
            }
            if ('' != jQuery('#address_field').val()) {
                this.fullAddress = this.fullAddress + jQuery('#address_field').val();
            }

        },

        clickOnMap: function () {
            google.maps.event.addListener(Address.map, 'click', function (event) {
                Address.finfCoords(Address.map, event);
            });
        },

        /**
         *
         * @param url
         * @param data
         * @param method
         */
        ajax: function (url, data, method) {
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                response: 'json',
                success: function (data) {
                    console.log(data);
                    //method(JSON.parse(data));
                },
                error: function (jqXHR) {
                    console.log(jqXHR.status);
                }

            });
        },
    };

    /**
     *
     * @type {{map: *, post, content: string, start: Function, addMarkers: Function, eventsOnMarkers: Function}}
     */
    /*
     var createAllMarkers = {

     map: Map.init(),
     post: JSON.parse(posts.dataset.post),
     content: '<div style="color: black;" class="wininfo"><div class="title">{{title}}</div><div id="test" class="text">{{body}}</div></div>',

     start: function() {
     createAllMarkers.addMarkers();
     },

     addMarkers: function() {

     var points = [];
     var markers = null;
     var content = null;

     for(var i = 0; i < createAllMarkers.post.length; i++) {

     points.push({'lat': parseFloat(createAllMarkers.post[i]['lat']), 'lng': parseFloat(createAllMarkers.post[i]['lng'])});
     markers = Map.addMarker(createAllMarkers.map, parseFloat(createAllMarkers.post[i]['lat']), parseFloat(createAllMarkers.post[i]['lng']));

     createAllMarkers.eventsOnMarkers(markers,
     mustache.render(createAllMarkers.content,
     {
     title: createAllMarkers.post[i]['title'],
     body: createAllMarkers.post[i]['body']
     })

     );
     }
     Map.centeringMap(points, createAllMarkers.map);
     },

     eventsOnMarkers: function(markers, content) {
     var infowindow = Map.infoWindows(content);

     markers.addListener('click', function() {
     infowindow.open(createAllMarkers.map, markers);
     });
     }
     };
     */
});
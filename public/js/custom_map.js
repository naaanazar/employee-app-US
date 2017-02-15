'use strict';

jQuery('document').ready(function () {

    /**
     * init map & create marker on map & change coordinates & address ( click on button )
     */
    jQuery(document).on('click', '.update-marker', function (event) {
        event.defaultPrevented = true;

        if (!jQuery('div').is('.init')) {
            Address.start();
        };

        google.maps.event.addListener(Address.map, 'click', function (event) {
            Address.finfCoords(Address.map, event);
        });

        Address.findAddress(Address.map);

        return false;
    });

    jQuery(document).on('click', '.hide-map', function (event) {
        event.preventDefault();

        if (jQuery('div').is('.init')) {
            jQuery('#init-map').css('display', 'none');
            jQuery('#map').removeClass('init');

        };
    });
});

/**
 *
 * @type {{fullAddress: string, marker: null, maps: null, finfCoords: Function, findAddress: Function, setAddress: Function}}
 */
var Address = {
    fullAddress: '',
    marker: null,
    maps: null,
    map: null,

    start: function() {
        jQuery('#init-map').css('display', 'block');
        Address.map = Address.maps.init();
        jQuery('#map').addClass('init');
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

                document.getElementById('city_field').value = city[1];
                document.getElementById('address_field').value = address[0].trim();
                document.getElementById('zip_field').value = city[0];
            }
        });

        this.marker = Address.maps.addMarker(map, event.latLng.lat(), event.latLng.lng());
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

                Address.marker = Address.maps.addMarker(map, results[0].geometry.location.lat(), results[0].geometry.location.lng());

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

    }
};

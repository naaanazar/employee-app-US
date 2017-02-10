define(['https://maps.googleapis.com/maps/api/js?key=AIzaSyDMgnsp7HMAHLR_ntjubgpnt3A8evQvsgg&libraries=geometry'], function(){
	var markers = [];
	return {
		init: function(){
			return new google.maps.Map(document.getElementById('map'), {
				center: {lat: 49.007073, lng: 30.2600184},
				zoom: 6
			});
		},
		addMarker: function (map, lat, lng) {
			var marker = new google.maps.Marker({
				position: {lat: lat, lng: lng},
				map: map
			});
			markers.push(marker);
			return marker;
		},
		clearMarker: function () {
			if(markers.length > 0) {
				for(var i = 0; i < markers.length; i++) {
					markers[i].setMap(null);
				}
			}
		},
		infoWindows: function(content) {
			return new google.maps.InfoWindow({
			    content: content
			});
		},
		centeringMap: function(points, map) {

			var latlngbounds = new google.maps.LatLngBounds();
			for (var i in points){
				latlngbounds.extend(points[i]);
			}
			map.setCenter( latlngbounds.getCenter(), map.fitBounds(latlngbounds));
		}
	};
});

	


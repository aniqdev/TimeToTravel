var marker;

function initMap() {
    var latitudeNode = document.getElementById("latitude");
    var longitudeNode = document.getElementById("longitude");

    var latitude = latitudeNode.value;
    var longitude = longitudeNode.value;

    if (!latitude || !longitude) {
        latitude = 55.751244;
        longitude = 37.618423;
    }

    var LatLng = new google.maps.LatLng(latitude, longitude);

    var mapProp = {
        center: LatLng,
        zoom: 12,
        draggableCursor: 'pointer'
    };
    var map = new google.maps.Map(document.getElementById("map"), mapProp);

    map.addListener('click', function (event) {
        placeMarker(event.latLng, map);
    });

    showCurrentMarker(map);

    latitudeNode.addEventListener('change', function (event) {
        var latLng = new google.maps.LatLng(event.target.value, longitudeNode.value);
        placeMarker(latLng, map);
    });

    longitudeNode.addEventListener('change', function (event) {
        var latLng = new google.maps.LatLng(latitudeNode.value, event.target.value);
        placeMarker(latLng, map);
    });
}

function showCurrentMarker(map) {
    var latitude = document.getElementById("latitude").value;
    var longitude = document.getElementById("longitude").value;

    if (latitude && longitude) {
        var LatLng = new google.maps.LatLng(latitude, longitude);
        marker = new google.maps.Marker({
            position: LatLng,
            map: map
        });
    }
}

function placeMarker(location, map) {
    if (marker == null) {
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
    } else {
        marker.setPosition(location);
    }

    var lat = document.getElementById("latitude");
    var long = document.getElementById("longitude");
    lat.value = location.lat();
    long.value = location.lng();
}

<style>

#map canvas{
	cursor: default;
}

.marker {
	background-color: transparent;
	cursor: pointer;
	z-index: 0;
}

.markerBack {
	width: 46px;
	height: 46px;
	border-radius: 50%;
	border: 2px solid #526AE8;  
	background-color: #526AE8;

	position: absolute;
	top: 50%;
	left: 50%;
	transform: translateX(-50%) translateY(-50%);
	z-index: 2;
    background-size: cover;
    background-position: center; 
}

.marker:hover .markerBack,
.marker.hovered .markerBack{
	background-color: #17a2b8;
}

.marker.active .markerBack{
	background-color: #17a2b8;
	border-color: #17a2b8;
}

.markerNumber {
	width: 24px;
	height: 24px;
	border-radius: 50%;  
	color: white;  
	background-color: #526AE8;  

	position: absolute;
	bottom: 8px;
	left: 8px;
	transform: translateX(+0px) translateY(+0px);
	z-index: 3;

	font-size: 16px;
	text-align: center;
	vertical-align: middle;
	line-height: 24px;
}
</style>
<!-- New Route Modal -->
<div class="modal fade" id="show_route_map_modal" tabindex="-1" aria-labelledby="show_route_map_modal_label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="show_route_map_modal_label">Карта</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div id="map" style="height: 500px;"></div>

			</div>
			<div class="modal-footer text left">
				
			</div>
		</div>
	</div>
</div>

<script src='https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css' rel='stylesheet'>  

<script>
function showRouteMapModal(routeId) {
	getRouteInfoAjax(routeId)
	$('#show_route_map_modal').modal('show')
}




var defaultMapStyle = 'mapbox://styles/mapbox/satellite-streets-v11'
var defaultRouteProfile = 'walking' /* walking, cycling, driving, driving-traffic */
var mapContainerId = 'map'

mapboxgl.accessToken = 'pk.eyJ1Ijoic2xhdjAxIiwiYSI6ImNrMHd5Yjg0ZDAweWkzY295ZGx6MmpscDYifQ.I5L8kZNRpOOaRzNBMcJkfg';
var mapboxOptions = {
	container: mapContainerId, // container ID
	// style: 'mapbox://styles/mapbox/streets-v11', // style URL
	style: defaultMapStyle,
	// locale: 'ru',
	center: [ 35.9006, 56.8584 ], // Tver
	zoom: 12 // starting zoom
}
var citiLatitude = document.getElementById(mapContainerId).dataset.latitude
var citiLongitude = document.getElementById(mapContainerId).dataset.longitude
if (citiLatitude && citiLongitude) {
	mapboxOptions.center = [citiLongitude, citiLatitude]
}
const map = new mapboxgl.Map(mapboxOptions)

// disable map rotation using right click + drag
map.dragRotate.disable(); 
// disable map rotation using touch rotation gesture
map.touchZoomRotate.disableRotation();

$('#show_route_map_modal').on('shown.bs.modal', function () { // chooseLocation is the id of the modal.
    map.resize();
});

var markersArr = []

var markerDigit = 1
function getRouteInfoCallback(route) {
	$('#show_route_map_modal_label').text(route.name)
	sr_removeMarkers()
	route.sights.forEach(function(sight, index) {
		if (index === 0) { 
			map.setCenter({
				lat: sight.latitude,
				lng: sight.longitude
			}) 
		}
		sr_addMarker(sight, {
			lat: sight.latitude,
			lng: sight.longitude
		}, markerDigit)
		markerDigit++
	})
	markerDigit = 1
	sr_addRouteLine( route.line_points )
}

function sr_removeRouteLine() {
	if(map.getLayer('route')) map.removeLayer('route')
	if(map.getSource('route')) map.removeSource('route')
}

function sr_removeMarkers() {
	markersArr.forEach(marker => marker.remove())
	markersArr.length = 0
}

function sr_addRouteLine( coordinates ) {

	sr_removeRouteLine()

	map.addLayer( {
		'id': 'route',
		'type': 'line',
		'source': {
			'type': 'geojson',
			'data': {
				'type': 'Feature',
				// 'properties': {},
				'geometry': {
					'type': 'LineString',
					'coordinates': coordinates
				}
			}
		},
		// 'layout': {
			// 'line-join': 'round',
			// 'line-cap': 'round',
			// 'line-opacity': 0.75
		// },
		'paint': {
			'line-color': '#526AE8',
			'line-width': 3
		}
	});
}

function sr_addMarker( sight, lngLat, markerDigit ) {

	var markerElement = document.createElement('div')
	markerElement.className = 'marker'

	var markerBack = document.createElement('div')
	markerBack.className = 'markerBack fuck'
	if(sight.images.length){
		var src = 'url("'+sight.images[0].preview+'")'
		markerBack.style.backgroundImage = src
	}
	var markerNumber = document.createElement('div')
	markerNumber.className = 'markerNumber'
	markerNumber.innerText = markerDigit

	markerElement.appendChild( markerBack )
	markerElement.appendChild( markerNumber )

	var marker = new mapboxgl.Marker( markerElement, {	anchor: 'center' } )
	marker.setLngLat( lngLat /* [ 35.9006, 56.8584 ]*/ )
	marker.addTo( map )

	markersArr.push(marker)
}

function getRouteInfoAjax(routeId) {
	$.get('/api/v1/getRoute?routeId=' + routeId, function(data) {
		if (data.status && data.status === 'ok') {
			getRouteInfoCallback(data.route)
		}
	}, 'json')
}
</script>
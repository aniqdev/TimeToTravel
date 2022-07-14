@extends('layouts.main')

@section('page-title')
Редактирование маршрута - <span class="route-name text-bold" onclick="routePageTitleClick()">{{ $route->name }}</span>
@endsection

@section('breadcrumbs')
<div class="float-sm-right">
	<button type="button" class="btn btn-primary mr-1" onclick="$('#main_route_form').submit()">Сохранить маршрут</button>
</div>
@endsection

@section('content')

<script src='https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css' rel='stylesheet' />
<style>
	.deg45 {
	--colr: #fbb;
		/*background-color: white;*/
		background-image: linear-gradient(-45deg, var(--colr) 10%, transparent 10%, transparent 50%, var(--colr) 50%, var(--colr) 60%, transparent 60%, transparent);
		background-size: 10px 10px;
		outline: 1px solid red;
		opacity: .6;
		z-index: 1;
}
.dev-square-hide .deg45{
	display: none;
}
.content-wrapper{
	display: flex;
	flex-direction: column;
}
.content-wrapper .content{
	flex: 1;
	position: relative;
	padding: 0;
	/*overflow-y: auto;*/
}
.content-wrapper .content .container-fluid{
	height: 100%;
	position: absolute;
}
body {
	font-family: sans-serif;
	margin: 0;
}

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

.marker.active .markerNumber{
	background-color: #17a2b8;
}

.pathDot {
	width: 16px;
	height:  16px;
	background-color: transparent;
	cursor: pointer;
	z-index: 0;
}
.pathDot.hidden{
	display: none;
}
.pathDotBack {
	width: 16px;
	height: 16px;
	border-radius: 50%;
	border: 1px solid #526AE8;  
	background-color: #526AE8;

	position: absolute;
	top: 50%;
	left: 50%;
	transform: translateX(-50%) translateY(-50%);
	z-index: 1;  
}
.pathDotNumber {
	width: 10px;
	height: 10px;
	border-radius: 50%;  
	color: white;  
	background-color: #eee;  

	position: absolute;
	top: 50%;
	left: 50%;
	transform: translateX(-50%) translateY(-50%);
	z-index: 3;

	font-size: 10px;
	text-align: center;
	vertical-align: middle;
	line-height: 10px;
}

.contextMenu {
	border-radius: 5px;
	border: 0;
	background-color: white;
	color: black;
	padding-top: 5px;
	padding-bottom: 5px;
	font-size: 14px;
}

.contextMenuElement {
	height: 26px;
	padding-left: 10px;
	padding-right:  10px;

	font-size: 14px;
	text-align: left;
	vertical-align: middle;
	line-height: 26px;
}
.contextMenuElement.ctm-disabled {
	display: none;
}

.contextMenuElement:hover {
	background-color: #526AE8;
	color: white;  
}

#map {
	display: inline-block;
}

#point-edit-control {
	display: inline-block;
	width: 200px;
	vertical-align: top;  
	display: none;
}

.mapbox-navigation{
	position: absolute;
	display: flex;
	z-index: 10;
}

#map-coordinates {
    padding: 0 8px;
    background-color: #17a2b8;
    border-radius: 0.15rem;
    color: white;
    width: 160px;
    text-align: center;
    display: none;
}

#map-controls {
	padding: 7px;
	background-color: white;
	width: 300px;
	margin: 0;
}
.add-route-form{
	padding: 0 8px;
}
.eclips{
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
}
#sights_sortable { list-style-type: none; margin: 0; padding: 0; }
#sights_sortable .ui-state-highlight { height: 2.5em; line-height: 1.2em; background: #17a2b8; }
.js-file-object{
	display: inline-block;
}
.js-object-files .remove-btn{
	background: #fb9d9d;
	border: 0;
	line-height: 18px;
	border-radius: 4px;
	color: #fff;
	right: -3px;
	top: -3px;
	z-index: 1;
}
.js-object-files .controls > *{
    background: #17a2b8c7;
    border: 0;
    line-height: 18px;
    border-radius: 3px;
    color: #fff;
    padding: 3px 8px;
}
.js-object-files .controls .remove-btn{
	background: #fb9d9d;
}
.sight-list-item.path-dot{
	display: none;
}
.js-sortable-images{
	display: flex;
	flex-wrap: wrap;
	/*overflow: hidden;*/
	/*width: 400px;*/
	position: relative;
	z-index: 999;
    justify-content: space-between;
}
.js-sortable-images .object-image{
	width: 24%;
    cursor: move;
    padding: 5px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
        margin-bottom: 4px;
}
.object-image img{
	max-width: 100%;
    max-height: 100px;
    margin: auto;
    display: block;
}
.object-image .object-title{
	font-size: 12px;
	margin-top: auto;
}
.map-search-wrapper{
    position: relative;
    display: inline-block;
}
.map-search-input{
    margin-bottom: -30px;
    height: 24px;
    width: 300px;
}
.map-search-results{
	position: absolute;
    top: 30px;
    list-style: none;
    background: #fff;
    padding: 0;
    margin: 0;
    width: 100%;
}
.map-search-results li{
	padding: 3px 8px;
	cursor: pointer;
}
.map-search-results li:hover,
.map-search-results li.active{
	background-color: #17a2b863;
}
</style>

<div class="row" style="height: 100%;">
	<div class="col-sm-4" style="overflow: auto; height: 100%;">
		{{-- route creation --}}
		<div class="container_">
			<div class="content">
				<form class="add-route-form" 
						id="main_route_form" 
						method="post" 
						enctype="multipart/form-data" 
						onsubmit="save_route(this, event)" 
						action="{{ route('routes.update', $route) }}" 
						accept-charset="UTF-8"
						data-routeid="{{ $route->id }}">
					@csrf
					@method('PUT')
					<input type="hidden" name="options[how_build_route]" value="{{ $route->options->how_build_route ?? 'manual' }}" id="how_build_route_input">
					<input type="hidden" name="options[how_search_places]" value="{{ $route->options->how_search_places ?? 'openstreetmap' }}" id="how_search_places_input">
					<input type="hidden" name="latitude" value="{{ $route->latitude }}" id="route_latitude">
					<input type="hidden" name="longitude" value="{{ $route->longitude }}" id="route_longitude">
					<input type="hidden" name="line_points" value="{{ json_encode($route->line_points) }}" id="route_line_points">
					{{-- ***************************** --}}
					<div class="card card-info card-outline">
						<a class="d-block w-100" data-toggle="collapse" href="#route_info" aria-expanded="true"> {{-- collapsed --}}
							<div class="card-header">
								<h5 class="card-title w-100 d-flex justify-content-between sight-title">Основная информация</h5>
							</div>
						</a>
						<div id="route_info" class="collapse show" data-parent="#main_route_form" style=""> {{-- show --}}
							<div class="card-body">
								@include('routes.blocks.edit-route-form')
							</div>
						</div>
					</div>
					{{-- ***************************** --}}
					<h5>Достопримечательности</h5>
					<ul id="sights_sortable">
						@foreach($route->sights as $sight)
							@include('routes.blocks.edit-sight-list-item')
						@endforeach
					</ul>
					<?php 
						$sight_ = json_decode('{"name":"{item_title}","images":[],"audio":[],"video":[]}');
					?>
					<template id="sights_list_item_tpl">
						@include('routes.blocks.edit-sight-list-item', ['sight' => $sight_, 'path_dot_class' => '{path_dot_class}'])
					</template>
					{{-- ***************************** --}}
					<div class="add-route mt-4">
						{{-- <button class="btn btn-primary main-btn" type="submit">Сохранить маршрут</button> --}}
					</div>
				</form>
			</div>
		</div>
{{-- /route creation --}}
	</div>
	<div class="col-sm-8 position-relative" style="min-height: 300px;">
		<div class="mapbox-navigation">

			{{-- <button type="button" class="btn btn-info btn-xs mr-1" onclick="$('#main_route_form').submit()">Сохранить</button> --}}
			{{-- <button type="button" class="btn btn-info btn-xs mr-1" onclick="buildRouteAuto()">Построить маршрут</button> --}}
			
			<select class="btn btn-info btn-xs mr-1" id='map-style-select' onchange='setMapStyle( this.value )'>
				<option value='mapbox://styles/mapbox/satellite-streets-v11'>Спутник</option>
				<option value='mapbox://styles/mapbox/streets-v11'>Карта</option>
				<option value='mapbox://styles/klapushnizza/cjjptyky60v1p2sqgx1sfo9xm'>Карта 2</option>
				<option value='mapbox://styles/mapbox/outdoors-v11'>Mapbox Outdoors</option>
				<option value='mapbox://styles/mapbox/light-v10'>Mapbox Light</option>
				<option value='mapbox://styles/mapbox/dark-v10'>Mapbox Dark</option>
				<option value='mapbox://styles/mapbox/satellite-v9'>Mapbox Satellite</option>
				<option value='mapbox://styles/mapbox/navigation-day-v1'>Mapbox Navigation Day</option>
				<option value='mapbox://styles/mapbox/navigation-night-v1'>Mapbox Navigation Night</option>

			</select>

			<select class="btn btn-info btn-xs mr-1" onchange='howBuildRouteChange( this.value )'>
				<option {{ (($route->options->how_build_route ?? '') === 'manual') ? 'selected' : '' }} value='manual'>Вручную</option>
				<option {{ (($route->options->how_build_route ?? '') === 'auto') ? 'selected' : '' }} value='auto'>Авто</option>
			</select>

			<button type="button" class="btn btn-info btn-xs px-2 mr-1" onclick="localize_ru()">Локализировать</button>

			<label class="m-0 px-1">
				<input onchange="devSquareChange()" type="checkbox" style="accent-color: #17a2b8; visibility: hidden;" title="dev mode">
			</label>

			<div class="mr-1" id='map-coordinates'>0.0, 0.0</div>

			<div class="map-search-wrapper">
				<input class="map-search-input form-control form-control-sm" type="text" placeholder="search..." id="map_search_input">

				<ul class="map-search-results" id="map_search_results"></ul>
			</div>

			<select class="btn btn-info btn-xs mr-1" onchange="howSearchPlaces( this.value )">
				<option {{ (($route->options->how_search_places ?? '') === 'openstreetmap') ? 'selected' : '' }} value='openstreetmap'>OpenStreetMap</option>
				<option {{ (($route->options->how_search_places ?? '') === 'mapbox') ? 'selected' : '' }} value='mapbox'>MapBox</option>
			</select>

		</div>


		<div class="route-create-map dev-square-hide" id='map'
			style='width: 100%; height: 100%;' 
			data-latitude="{{ $route->city->latitude }}"
			data-longitude="{{ $route->city->longitude }}">
		</div>
	</div>
</div>


<script>
var log = console.log

// $(function() {
// 	$("#sights_sortable").sortable({
// 		placeholder: "ui-state-highlight",
// 		update: function( event, ui ) {
// 			updateSightsOrder()
// 		}
// 	});

// 	$( "#sights_sortable" ).disableSelection();
// })

// sight title update
// $('#sights_sortable').on('input', '.sight-title-input', function (e) {
// 	var sight_title = $(this).closest('.card').find('.sight-title')
// 	sight_title.text(this.value)
// 	sight_title.attr('title', this.value)
// })



var markersArr = []
var sightDrag = false
var pathDotDrag = false
var defaultMapStyle = localStorage.getItem('mapStyle') || 'mapbox://styles/mapbox/satellite-streets-v11'
var defaultRouteProfile = 'walking' /* walking, cycling, driving, driving-traffic */
var mapContainerId = 'map'
var how_build_route = $('#how_build_route_input').val()
var how_search_places = $('#how_search_places_input').val()

mapboxgl.accessToken = 'pk.eyJ1Ijoic2xhdjAxIiwiYSI6ImNrMHd5Yjg0ZDAweWkzY295ZGx6MmpscDYifQ.I5L8kZNRpOOaRzNBMcJkfg';
var mapboxOptions = {
	container: mapContainerId, // container ID
	// style: 'mapbox://styles/mapbox/streets-v11', // style URL
	style: defaultMapStyle,
	// locale: 'ru',
	center: [ 35.9006, 56.8584 ], // Tver
	zoom: 12 // starting zoom
}

var route_lat =	$('#route_latitude').val()
var route_lng =	$('#route_longitude').val()
if (route_lat && route_lng) {
	mapboxOptions.center = [route_lng, route_lat]
}

const map = new mapboxgl.Map(mapboxOptions)

// map.on('load', localize_ru)
function localize_ru() {
	let labels = [ 'country-label', 'state-label', 
	'settlement-label', 'settlement-subdivision-label', 
	'airport-label', 'poi-label', 'water-point-label', 
	'water-line-label', 'natural-point-label', 
	'natural-line-label', 'waterway-label', 'road-label' ];

	labels.forEach(label => {
	    map.setLayoutProperty(label, 'text-field', ['get','name_ru']);
	});
}

// const popup = new mapboxgl.Popup({ offset: 25 }).setText(
// 	'Route center'
// );
const routeCenterMarker = new mapboxgl.Marker()
	.setLngLat([0,0])
	// .setPopup(popup)
	.addTo(map);

const searchResultMarker = new mapboxgl.Marker({ color: 'blue' })
	.setLngLat([0,0])
	// .setPopup(popup)
	.addTo(map);

// Add zoom and rotation controls to the map.
map.addControl(new mapboxgl.NavigationControl());

map.on('mouseup', e => {
	sightDrag = false
	pathDotDrag = false
})

// disable map rotation using right click + drag
map.dragRotate.disable(); 
// disable map rotation using touch rotation gesture
map.touchZoomRotate.disableRotation();

document.querySelector('#map-style-select').value = defaultMapStyle
document.querySelector('#profile').value = defaultRouteProfile

/* disable default context menu */
window.addEventListener('contextmenu', function (e) {
	if( isMapClick( e.target ) )
		e.preventDefault() /* отключаем стандартное контекстное меню для карты, чтобы не мешало */
}, false)

window.addEventListener('mousedown', e => {
	if( !isMapClick( e.target ) ) /* если нажали не по карте */
		hideContextMenu() /* скрываем контекстное меню */
})


var clickLngLat = null
var clickedMarker = null
var clickedMarkerElement = null
var clickedPathDotElement = null
var markerDigit = 1
var sightDrag = false
var lineColor = 'rgba(82, 106, 232, 0.1)'
var lineColorHover = 'rgba(82, 106, 232, 1)'

map.on('click', 'route-hover', e => {

})

var lineClickLngLat = null
map.on('mousedown', 'route-hover', e => {
	lineClickLngLat = e.lngLat
	return false
})

map.on('mouseenter', 'route-hover', e => {
	map.setPaintProperty('route-hover', 'line-color', lineColorHover);
})

map.on('mouseleave', 'route-hover', e => {
	map.setPaintProperty('route-hover', 'line-color', lineColor);
})

map.on("mousedown", (e) => {
	if( contextMenuShown && e.originalEvent.target.className !== 'contextMenuElement' ) {
		 hideContextMenu()
		 return false
	}
	if( e.originalEvent.button === 2 ) {
		if (isPathDotClick(e.originalEvent.target)) {
			showContextMenu( 'dot', e.lngLat )
			return false
		}
		if (isMarkerClick(e.originalEvent.target)) {
			showContextMenu( 'point', e.lngLat )
			return false
		}
		if(shallowEqual(lineClickLngLat, e.lngLat)){
			showContextMenu( 'line', e.lngLat )
			return false
		}
		showContextMenu( 'map', e.lngLat )
	}
})


map.on('mousemove', e => {
	// document.querySelector('#map-coordinates').innerText = e.lngLat.lat.toFixed(4)+', '+e.lngLat.lng.toFixed(4)
	if (sightDrag) {
		clickedMarkerElement.marker.setLngLat(e.lngLat)
		destroySquares()
	}
	if (pathDotDrag) {
		clickedPathDotElement.marker.setLngLat(e.lngLat)
		destroySquares()
	}

})


// обозначим 3 сущности
// sightListItem - li с информацией о достопримечательности
// marker - точка на карте (new mapboxgl.Marker())
// markerElement - стилизованый div элемент
// каждая из них имеет ссылку друг на друга
function addMarker( lngLat, sightListItem = false ) {

	if(!sightListItem) sightListItem = document.querySelector('#sights_sortable li:last-child')

	var markerElement = document.createElement('div')
	markerElement.className = 'marker'
	markerElement.addEventListener('mousedown', e => {
		saveClickedPosition(e)
		clickedMarkerElement = markerElement
		if( e.buttons === 1 ) {
			e.stopPropagation()
			sightDrag = true
		}
	});

	markerElement.addEventListener('mouseup', e => {
		sightDrag = false
		pathDotDrag = false
		setItemLngLat(e.currentTarget)
		buildRoute()
	})

	markerElement.addEventListener('click', e => {
		if (sameMousePosition(e)) {
			markerClick(marker)
		}
	});

	var markerBack = document.createElement('div')
	markerBack.className = 'markerBack'

	var markerNumber = document.createElement('div')
	markerNumber.className = 'markerNumber'
	markerNumber.innerText = markerDigit

	markerElement.appendChild( markerBack )
	markerElement.appendChild( markerNumber )

	var marker = new mapboxgl.Marker( markerElement, {	anchor: 'center' } )
	marker.setLngLat( lngLat /* [ 35.9006, 56.8584 ]*/ )
	marker.addTo( map )


	// var sight_id = $(sightListItem).find('.sight-id').val()


	sightListItem.marker = markerElement.marker = marker
	marker.markerElement = sightListItem.markerElement = markerElement
	marker.sightListItem = markerElement.sightListItem = sightListItem

	markerDigit++
	return marker
}


function addPathDotMarker( lngLat, sightListItem ) {

	var markerElement = document.createElement('div')
	if (how_build_route === 'auto') markerElement.className = 'pathDot hidden'
	else markerElement.className = 'pathDot'
	markerElement.addEventListener('mousedown', e => {
		clickedPathDotElement = markerElement
		if( e.buttons === 1 ) {
			e.stopPropagation()
			pathDotDrag = true
		}
	})
	markerElement.addEventListener('mouseup', e => {
		pathDotDrag = false
		sightDrag = false
		setItemLngLat(e.currentTarget)
		buildRoute() 
	})

	var pathDotBack = document.createElement('div')
	pathDotBack.className = 'pathDotBack'

	var pathDotNumber = document.createElement('div')
	pathDotNumber.className = 'pathDotNumber'
	// pathDotNumber.innerText = 0

	markerElement.appendChild( pathDotBack )
	markerElement.appendChild( pathDotNumber )

	var marker = new mapboxgl.Marker( markerElement, { anchor: 'center' } )
	marker.setLngLat( lngLat /* [ 35.9006, 56.8584 ]*/ )
	marker.addTo( map )

	sightListItem.marker = markerElement.marker = marker
	marker.markerElement = sightListItem.markerElement = markerElement
	marker.sightListItem = markerElement.sightListItem = sightListItem

	return marker
}

function updateMarkersArr() {
	markersArr = []
	$('#sights_sortable li').each(function(index) {
		markersArr.push({
			sight_id: $(this).find('.sight-id').val(),
			lngLat: {
				lat: $(this).find('.sight-latitude').val(),
				lng: $(this).find('.sight-longitude').val()
			}
		})
	})
	updateRouteOrigin()
	return markersArr
}

// draw initial markers
$('#sights_sortable li').each(function(index) {
	if (this.classList.contains('path-dot')) {
		addPathDotMarker({
			lat: $(this).find('.sight-latitude').val(),
			lng: $(this).find('.sight-longitude').val()
		}, this)	
	}else{
		addMarker({
			lat: $(this).find('.sight-latitude').val(),
			lng: $(this).find('.sight-longitude').val()
		}, this)
	}
})
updateMarkersArr()
map.on('load', function() {
	buildRoute()
})
setMarkerImages()
updateSightsOrder()


function clearAll() {
	removeRouteLine()
}


function removeMarker() {
	removeSightAjax(clickedMarkerElement.sightListItem)
	clickedMarkerElement = null
}

function removePathDot() {
	removeSightAjax(clickedPathDotElement.sightListItem)
	clickedPathDotElement = null
}


var contextMenuMarker
var contextMenuShown = false


createContextMenu()

function createContextMenu() {
	var menuDiv = document.createElement('div')
	menuDiv.className = 'contextMenu'

	var addMarkerDivBefore = document.createElement('div')
	addMarkerDivBefore.id = 'add-marker-before'
	addMarkerDivBefore.className = 'contextMenuElement'
	addMarkerDivBefore.innerText = '+ Добавить маркер в начало'
	addMarkerDivBefore.onclick = e => {
		if( e.target.classList.contains('ctm-disabled') )	return

		var lngLat = contextMenuMarker._lngLat
		var index = -1 // should be undefined to add marker to the end

		createSightAjax(lngLat, index, function(new_sight_id) {
			var sightListItem = add_sigts_list_item(lngLat, index, new_sight_id, '')
			addMarker(lngLat, sightListItem)
			sightListItem.querySelector('a[data-toggle="collapse"]').click()
			updateSightsOrder()
			updateMarkersArr()
			buildRoute()
		})

		hideContextMenu()
	}


	var addMarkerDivAfter = document.createElement('div')
	addMarkerDivAfter.id = 'add-marker-after'
	addMarkerDivAfter.className = 'contextMenuElement'
	addMarkerDivAfter.innerText = '+ Добавить маркер в конец'
	addMarkerDivAfter.onclick = e => {
		if( e.target.classList.contains('ctm-disabled') )	return

		var lngLat = contextMenuMarker._lngLat
		var index // should be undefined to add marker to the end

		createSightAjax(lngLat, index, function(new_sight_id) {
			var sightListItem = add_sigts_list_item(lngLat, index, new_sight_id, '')
			addMarker(lngLat, sightListItem)
			sightListItem.querySelector('a[data-toggle="collapse"]').click()
			updateSightsOrder()
			updateMarkersArr()
			buildRoute()
		})

		hideContextMenu()
	}


	var addPathMarkerDiv = document.createElement('div')
	addPathMarkerDiv.id = 'add-marker-inside'
	addPathMarkerDiv.className = 'contextMenuElement'
	addPathMarkerDiv.innerText = '+ Добавить маркер пути'
	addPathMarkerDiv.onclick = e => {
		if( e.target.classList.contains('ctm-disabled') ) return

		var lngLat = contextMenuMarker._lngLat
		var index = getPathDotsAround(lngLat)

		createSightAjax(lngLat, index, function(new_sight_id) {
			var sightListItem = add_sigts_list_item(lngLat, index, new_sight_id, '')
			addMarker(lngLat, sightListItem)
			sightListItem.querySelector('a[data-toggle="collapse"]').click()
			updateSightsOrder()
			updateMarkersArr()
			buildRoute()
		})

		hideContextMenu()
	}

	var removeMarkerDiv = document.createElement('div')
	removeMarkerDiv.id = 'remove-marker'
	removeMarkerDiv.className = 'contextMenuElement'
	removeMarkerDiv.innerText = '- Удалить маркер'
	removeMarkerDiv.onclick = e => {

		if( e.target.classList.contains('ctm-disabled') ) return

		removeMarker()
		hideContextMenu()
	}

	var addPathDotDiv = document.createElement('div')
	addPathDotDiv.id = 'add-path-dot'
	addPathDotDiv.className = 'contextMenuElement'
	addPathDotDiv.innerText = '+ Добавить точку пути'
	addPathDotDiv.onclick = e => {

		if( e.target.classList.contains('ctm-disabled') ) return
		
		var lngLat = contextMenuMarker._lngLat
		var index = getPathDotsAround(lngLat)

		createSightAjax(lngLat, index, function(new_sight_id) {
			var sightListItem = add_sigts_list_item(lngLat, index, new_sight_id)
			addPathDotMarker( lngLat, sightListItem )
			updateSightsOrder()
			updateMarkersArr()
			buildRoute()
		})

		hideContextMenu()
	}

	var removePathDotDiv = document.createElement('div')
	removePathDotDiv.id = 'remove-path-dot'
	removePathDotDiv.className = 'contextMenuElement'
	removePathDotDiv.innerText = '- Удалить точку'
	removePathDotDiv.onclick = e => {

		if( e.target.classList.contains('ctm-disabled') ) return

		removePathDot()
		hideContextMenu()
	}

	menuDiv.appendChild( addMarkerDivBefore )
	menuDiv.appendChild( addMarkerDivAfter )
	menuDiv.appendChild( addPathMarkerDiv )
	menuDiv.appendChild( removeMarkerDiv )
	menuDiv.appendChild( addPathDotDiv )
	menuDiv.appendChild( removePathDotDiv )

	contextMenuMarker = new mapboxgl.Marker( menuDiv, {	anchor: 'top-left' } )
}

function getPathDotsAround( lngLat ) {

	for( var i=1; i < markersArr.length; i++ )
		if( 
				( ( markersArr[i-1].lngLat.lng < lngLat.lng && lngLat.lng < markersArr[i].lngLat.lng ) || ( markersArr[i].lngLat.lng < lngLat.lng && lngLat.lng < markersArr[i-1].lngLat.lng ) ) && 
				( ( markersArr[i-1].lngLat.lat < lngLat.lat && lngLat.lat < markersArr[i].lngLat.lat ) || ( markersArr[i].lngLat.lat < lngLat.lat && lngLat.lat < markersArr[i-1].lngLat.lat ) )
			)
			return i - 1

	return undefined
}

var countDistanceAutomaticly = $('#auto_distance_chbx').is(':checked') // this value should come from server
function countDistance() {
	if(!countDistanceAutomaticly) return false
	var distance = 0
	for( var i=1; i < markersArr.length; i++ ){
		distance += +Number( vincentyGreatCircleDistance( markersArr[i-1].lngLat.lng, markersArr[i-1].lngLat.lat, markersArr[i].lngLat.lng, markersArr[i].lngLat.lat ).toFixed(3) )
	}
	$('#route_length').val(distance.toFixed(2))
}

$('#auto_distance_chbx').on('change', function() {
	if ($(this).is(':checked')) {
		countDistanceAutomaticly = true
		$("#route_length").prop("readonly",true);
		countDistance()
	}else{
		countDistanceAutomaticly = false
		$("#route_length").prop("readonly",false);
	}
})

function deg2rad( deg )  {
  return deg * Math.PI / 180;
}

function vincentyGreatCircleDistance( longitudeFrom, latitudeFrom, longitudeTo, latitudeTo, earthRadius = 6371000 )
{
  var lonFrom = deg2rad( longitudeFrom )
  var latFrom = deg2rad( latitudeFrom )
  var lonTo = deg2rad( longitudeTo )
  var latTo = deg2rad( latitudeTo )

  var lonDelta = lonTo - lonFrom
  var a = Math.pow( Math.cos( latTo ) * Math.sin( lonDelta ), 2  ) +
		  Math.pow( Math.cos( latFrom ) * Math.sin( latTo ) - Math.sin( latFrom ) * Math.cos( latTo ) * Math.cos( lonDelta ), 2 )
  var b = Math.sin( latFrom ) * Math.sin( latTo ) + Math.cos( latFrom ) * Math.cos( latTo ) * Math.cos( lonDelta );

  var angle = Math.atan2( Math.sqrt( a ), b )
  return angle * earthRadius;
}

function showContextMenu( type, clickLngLat, markerLngLat  ) {

	contextMenuMarker._element.querySelectorAll('.contextMenuElement').forEach(el => {
		el.classList.add('ctm-disabled')
	})

	if( type === 'map' ) {
		contextMenuMarker._element.querySelector('#add-marker-before').classList.remove('ctm-disabled')
		contextMenuMarker._element.querySelector('#add-marker-after').classList.remove('ctm-disabled')
	}
	
	if( type === 'point' ) {
		contextMenuMarker._element.querySelector('#remove-marker').classList.remove('ctm-disabled')
	}
	
	if( type === 'dot' ) {
		contextMenuMarker._element.querySelector('#remove-path-dot').classList.remove('ctm-disabled')
	}
	
	if( type === 'line' ) {
		contextMenuMarker._element.querySelector('#add-path-dot').classList.remove('ctm-disabled')
		contextMenuMarker._element.querySelector('#add-marker-inside').classList.remove('ctm-disabled')
	}

	contextMenuMarker.setLngLat( clickLngLat )
	contextMenuMarker.addTo( map )
	contextMenuShown = true
} 


function hideContextMenu() {
	contextMenuMarker.remove()
	contextMenuShown = false
}

var showSuares = false
function buildRoute() {

	if (window.how_build_route === 'auto') {
		buildRouteAuto()
	}else{
		buildRouteManual()
	}

	if(showSuares) updateSquares()
	
	countDistance()
}

function buildRouteManual() {
	var coords = []
	markersArr.forEach(function(marker) {
		coords.push(lngLat_to_coords(marker.lngLat))
	})
	if(coords.length) addRouteLine(coords)
}

function buildRouteAuto() {
	var profile = $('#profile').val()
	// var coords = markersArr.map( item => item.lngLat.lng + ',' + item.lngLat.lat ).join(';')

	var coords = $('.sight-list-item:not(.path-dot)').map(function(indx){
		return $(this).find('.sight-longitude').val()+','+$(this).find('.sight-latitude').val()
	}).get().join(';')

	var url = 'https://api.mapbox.com/directions/v5/mapbox/'+profile+'/'+coords+'?geometries=geojson&steps=true&access_token='+mapboxgl.accessToken

	$.get(url, function(data) {
		if(data.routes[0].geometry.coordinates) addRouteLine(data.routes[0].geometry.coordinates)
	})
}


map.on('zoom', function () {
	updateSquares()	
})


function devSquareChange() {
	showSuares = !document.getElementById(mapContainerId).classList.toggle('dev-square-hide')
	if (showSuares) {
		updateSquares()
	}else{
		destroySquares()
	}
}

function destroySquares() {
	document.querySelectorAll('.dev-squares').forEach(el => el.remove())
}

function updateSquares() {
	var sightListItems = document.querySelectorAll('.sight-list-item')
	if (sightListItems.length > 1) {
		destroySquares()
		for( var i=1; i < sightListItems.length; i++ ){
			var marker_1 = sightListItems[i-1].marker
			var marker_2 = sightListItems[i].marker
			var matrix_1 = getMatrix(marker_1.markerElement)
			var matrix_2 = getMatrix(marker_2.markerElement)
			var squareWidth = Math.abs(matrix_1.x - matrix_2.x)
			var squareHeight = Math.abs(matrix_1.y - matrix_2.y)
			var squareElement = document.createElement('div')
			squareElement.className = 'deg45 dev-squares'
			squareElement.style.position = 'absolute'
			squareElement.style.width = squareWidth + 'px'
			squareElement.style.height = squareHeight + 'px'

			if (matrix_1.x > matrix_2.x) {
				squareElement.style.left = '-' + squareWidth + 'px'
			}
			if (matrix_1.y > matrix_2.y) {
				squareElement.style.top = '-' + squareHeight + 'px'
			}

			marker_1.markerElement.appendChild(squareElement)
		}
	}
}

function getMatrix(elem) {
	if(!elem) return { x: 0, y: 0 }
	var style = window.getComputedStyle(elem);
		var matrix = new WebKitCSSMatrix(style.transform);
		return {
			x: matrix.m41 || 0,
			y: matrix.m42 || 0,
		}
}


function addRouteLine( coordinates ) {
log('RouteLine length:', coordinates.length)
	$('#route_line_points').val(JSON.stringify(coordinates))

	removeRouteLine()

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

	map.addLayer( {
		'id': 'route-hover',
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
		'layout': {
			'line-join': 'round',
			'line-cap': 'round',
			// 'line-opacity': 0.2,
			// 'visibility': 'none'
			// 'line-opacity': 0.22,
		},
		'paint': {
			// 'line-color': '#526AE8',
			'line-color': lineColor,
			'line-width': 14,
			// 'line-opacity': 0.22,
		}
	});
}


function removeRouteLine() {
	if(map.getLayer('route')) map.removeLayer('route')
	if(map.getSource('route')) map.removeSource('route')

	if(map.getLayer('route-hover')) map.removeLayer('route-hover')
	if(map.getSource('route-hover')) map.removeSource('route-hover')
}

function hasRoute() {
	return map.getLayer('route')
}




function addVideoInputClick(add_input_button) {
	var video_input = `<input name="video[]" type="text" class="form-control mb-2" placeholder="youtube link">`
	$(add_input_button).closest('.video-input-wrapper').append(video_input)
}

var item_num = document.querySelectorAll('#sights_sortable li').length + 1
function add_sigts_list_item(lngLat, index = undefined, sight_id = 0, item_title = '€€') {

	var $sights_list_item_tpl_html = $('#sights_list_item_tpl').html()
	if(!$sights_list_item_tpl_html) return false
	
	$sights_list_item_tpl_html = $sights_list_item_tpl_html
		.replaceAll('{sight_id}', sight_id)
		.replaceAll('{item_num}', item_num)
		// .replaceAll('{marker_digit}', markerDigit)
		.replaceAll('{item_title}', item_title)
		.replace('{path_dot_class}', item_title === '€€' ? 'path-dot' : '')
		.replaceAll('{latitude}', lngLat.lat)
		.replaceAll('{longitude}', lngLat.lng)

	var sightListItem = $($sights_list_item_tpl_html)[0]

	if(index === undefined) $('#sights_sortable').append(sightListItem)
	else if(index === -1) $('#sights_sortable').prepend(sightListItem)
	else $('#sights_sortable li').eq(index).after(sightListItem)

	item_num++
	return sightListItem
}

function updateSightsOrder() {
	var num = 1
	$('input.sight-priority').each(function(i) {
		this.value = num++
	})

	document.querySelectorAll('.sight-list-item:not(.path-dot)').forEach( (sightListItem, index) => {
		$(sightListItem).find('.js-item-num-badge').text(index + 1)
		$(sightListItem.markerElement).find('.markerNumber').text(index + 1)
	})
}


function setRouteCoords(lngLat) {
	$('#route_latitude').val(lngLat.lat)
	$('#route_longitude').val(lngLat.lng)
}


function removeSight(button) {
	var sightListItem = button.closest('.sight-list-item')
	removeSightAjax(sightListItem)
}

function removeSightAjax(sightListItem) {
	$.post('/routes/removeSight', {
		sight_id: $(sightListItem).find('.sight-id').val(),
		_token: jQuery('meta[name="csrf-token"]').attr('content'),
	}, function (data) {
		if (data.status && data.status === 'ok') {
			sightListItem.marker.remove()
			sightListItem.remove()
			updateMarkersArr()
			updateSightsOrder()
			buildRoute()
			alert('success', data.message || 'Success!')
		}else{
			alert('danger', data.message || 'Error!')
		}
	}, 'json')
}


function createSightAjax(lngLat, index, callback) {
	$.post('/routes/createSight', {
		route_id: $('#main_route_form').data('routeid'),
		name: (index !== undefined && index !== -1) ? '€€' : '',
		latitude: lngLat.lat,
		longitude: lngLat.lng,
		_token: jQuery('meta[name="csrf-token"]').attr('content'),
	}, function (data) {
		if (data.status && data.status === 'ok' && data.sight_id) {
			callback(data.sight_id)
			alert('success', data.message || 'Success!')
		}else{
			alert('danger', data.message || 'Error!')
		}
	}, 'json')
}


$('#main_route_form').on('show.bs.collapse', function (www) {
	var li = www.target.closest('.sight-list-item')
	if(li) li.marker._element.classList.add('active')
})
$('#main_route_form').on('hide.bs.collapse', function (www) {
	var li = www.target.closest('.sight-list-item')
	if(li) li.marker._element.classList.remove('active')
})


function toCenter(event, coords) {
	event.stopPropagation()
	event.preventDefault()
	map.setCenter( coords )
}


function sightListItemMouseenter(sightListItem) {
	sightListItem.marker._element.classList.add('hovered')
}


function sightListItemMouseleave(sightListItem) {
	sightListItem.marker._element.classList.remove('hovered')
}

function searchPlace(query, callback) {
	query = encodeURIComponent(query)
	log(how_search_places)
	if (how_search_places === 'openstreetmap') {
		var url = 'https://nominatim.openstreetmap.org/search?format=json&q='+query+'&limit=3'
	}else{
		var url = "https://api.mapbox.com/geocoding/v5/mapbox.places/"+query+".json?limit=3&access_token=" + mapboxgl.accessToken
	}
	$.get(url, function(data) {
		data = adaptData(data)
		callback(data)
	})
}

function adaptData(data) {
	if (how_search_places === 'openstreetmap'){
		if (data.length > 0) {
			data = data.map(res_item => {
				return {
					name: res_item.display_name,
					longitude: res_item.lon,
					latitude: res_item.lat,
				}
			})
		}else{
			data = []
		}
	}else{
		if(data.features && data.features.length > 0){
			data = data.features.map(res_item => {
				return {
					name: res_item.place_name,
					longitude: res_item.center[0],
					latitude: res_item.center[1],
				}
			})
		}else{
			data = []
		}
	}
	return data
}


function updateRouteOrigin() {
	if(!markersArr.length) return false
	var lat_min = 90
	var lat_max = -90
	var lng_min = 999
	var lng_max = 0
	markersArr.forEach(function(item) {
		// log(item.lngLat)
		if (item.lngLat.lng > lng_max) lng_max = item.lngLat.lng
		if (item.lngLat.lng < lng_min) lng_min = item.lngLat.lng
		if (item.lngLat.lat > lat_max) lat_max = item.lngLat.lat
		if (item.lngLat.lat < lat_min) lat_min = item.lngLat.lat
	})
	var lat_mid = (+lat_min + +lat_max) / 2
	var lng_mid = (+lng_min + +lng_max) / 2
	routeCenterMarker.setLngLat([lng_mid, lat_mid])
	setRouteCoords({lng:lng_mid, lat:lat_mid})
}

var imageCard
function editImageTitleModal(button) {
	var modal = $('#edit_image_title_modal')
	imageCard = button.closest('.js-file-object')
	modal.find('.js-img-preview').attr('src', imageCard.dataset.imagepreview)
	modal.find('.js-img-title-input').val(imageCard.title)
	modal.find('.js-object-type').val(button.closest('.js-file-object-data').dataset.object)
	modal.find('.js-img-id').val(imageCard.dataset.imageid)
	modal.modal('show')
}

function editImageTitleSubmit(form, event) {
	event.preventDefault()
	var modal = $('#edit_image_title_modal')
	$.post('/routes/updateImageTitle', $(form).serialize(), function(data) {
		if (data.status && data.status === 'ok') {
			modal.modal('hide')
			imageCard.title = modal.find('.js-img-title-input').val()
			$(imageCard).find('.object-title').html(data.title)
			alert('success', 'Обновлено')
		}
	})
}


var audioCard
function editAudioTitleModal(button) {
	audioCard = button.closest('.js-file-object')
	var modal = $('#edit_audio_title_modal')
	$('#modal_audio_preview_src').attr('src', audioCard.dataset.audiourl)
	$('#modal_audio_title_input').val(audioCard.title)
	$('#modal_audio_object_input').val(button.closest('.js-file-object-data').dataset.object)
	$('#modal_audio_id_input').val(audioCard.dataset.audioid)
	modal.modal('show')
}

function editAudioTitleSubmit(form, event) {
	event.preventDefault()
	var modal = $('#edit_audio_title_modal')
	$.post('/routes/updateAudioTitle', $(form).serialize(), function(data) {
		if (data.status && data.status === 'ok') {
			modal.modal('hide')
			audioCard.title = modal.find('.js-img-title-input').val()
			$(audioCard).find('.audio-title').html(data.title)
			alert('success', 'Обновлено')
		}
	})
}














function routePageTitleClick() {
	$('a[href="#route_info"].collapsed').click()
	$('input[name="name"]').focus()
}


$(updateSortImagesEvents) // jQuery ready

function updateSortImagesEvents() {
	document.querySelectorAll('.js-sortable-images').forEach( cardsWrapper => {
	    cardsWrapper.grabbable();
	    cardsWrapper.removeEventListener('dragged', sortImagesEvent)
	    cardsWrapper.addEventListener('dragged', sortImagesEvent.bind(cardsWrapper))
	})
}

function sortImagesEvent(event) {
    var object = event.target.closest('.js-file-object-data').dataset.object
    var objectId = event.target.closest('.js-file-object-data').dataset.objectid
    var list = []
    this.querySelectorAll('.object-image').forEach(imageCard => {
    	list.push(imageCard.dataset.imageid)
    })
    $.post('/routes/updateRouteFilesOrder', {
    	_token: $('meta[name="csrf-token"]').attr('content'),
    	object: object,
    	objectId: objectId,
    	list: list,
    })
}

function markerClick(marker) {
	marker.sightListItem.querySelector('a[data-toggle="collapse"]').click()
}


function removeObjectFileClick(button, object_type, object_id) {
	$.post('/routes/removeObjectFile', {
		object_type,
		object_id,
		_token: $('meta[name="csrf-token"]').attr('content'),
	}, function (data) {
		if (data.status && data.status === 'ok') {
			button.closest('.js-file-object').remove()
		}
	}, 'json')
}


function save_route(form, event) {

	event.preventDefault()

	var formData = new FormData(form)

	$.ajax(form.action, {
		method: 'POST',
		data: formData,
		cache: false,
		processData: false,
		contentType: false,
		success: function (data) {
			if (data && data.status === 'ok') {
				if(data.preview_url) $('#edit_route_preview_img').attr('src', data.preview_url)
				alert('success', data.message || 'Success!')
				// form.reset()
			}else{
				if (data.errors && data.errors.length) {
					data.errors.forEach(err => alert('danger', err))
				}else{
					alert('danger', data.message || 'Error!')
				}
			}
		},
		error: function () {
			alert('danger', 'Server error!')
		},
		complete: function () {},
	});
}


function lngLat_to_coords( lngLat ) {
	return [ lngLat.lng, lngLat.lat ]
}


function coords_to_lngLat( arr ) {
	return { lng: arr[0], lat: arr[1] }
}


function setItemLngLat(markerElement) {
	var lngLat = markerElement.marker._lngLat
	$(markerElement.sightListItem).find('.sight-latitude').val(lngLat.lat)
	$(markerElement.sightListItem).find('.sight-longitude').val(lngLat.lng)
	updateMarkersArr()
}

function shallowEqual(object1, object2) {
	if(!object1) return false
	if(!object2) return false
	const keys1 = Object.keys(object1);
	const keys2 = Object.keys(object2);
	if (keys1.length !== keys2.length) {
		return false;
	}
	for (let key of keys1) {
		if (object1[key] !== object2[key]) {
				return false;
		}
	}
	return true;
}

add_action('setMarkerImages', setMarkerImages)
function setMarkerImages() {
	$('#sights_sortable li').each(function(index) {
		var src = $(this).find('.object-image:first img').attr('src')
		if(src){
			$(this.markerElement).find('.markerBack').css('background-image', 'url('+src+')')
		}
	})
}

var clickedPosition = null
function saveClickedPosition(event) {
	clickedPosition = { clientX: event.clientX, clientY: event.clientY }
}
function sameMousePosition(event) {
	var nowClickedPosition = { clientX: event.clientX, clientY: event.clientY }
	return shallowEqual(nowClickedPosition, clickedPosition)
}

function isMapClick( element ) {
	return element.closest('.mapboxgl-map')
}

function isMarkerClick( element ) {
	return element.closest('.marker')
}

function isPathDotClick( element ) {
	return element.closest('.pathDot')
}

function sightTitleOninput(input) {
	var sight_title = $(input).closest('.card').find('.sight-title')
	sight_title.text(input.value)
	sight_title.attr('title', input.value)
}

function howBuildRouteChange(value) {
	window.how_build_route = value
	$('#how_build_route_input').val(value)
	hideShowPathDots(value)
	buildRoute()
}

function hideShowPathDots(how_build_route) {
	if (how_build_route === 'auto') {
		document.querySelectorAll('.path-dot').forEach( item => $(item.markerElement).hide() )
	}
	if (how_build_route === 'manual') {
		document.querySelectorAll('.path-dot').forEach( item => $(item.markerElement).show() )
	}
}

function howSearchPlaces(value) {
	window.how_search_places = value
	$('#how_search_places_input').val(value)
	$('#map_search_results').html('')
	doSearch($('#map_search_input').val())
	$('#map_search_input').focus()
	// buildRoute()
}

function setMapStyle( style ) {
	map.setStyle( style )
	localStorage.setItem('mapStyle', style);
	setTimeout(function() {
		// localize_ru()
		buildRoute()
	}, 100)
}

function setCenter( coords ){
	coords = JSON.parse( coords )
	map.setCenter( coords )
}

function change_city(select) {
	setCenter(select.options[select.selectedIndex].dataset.center)
}

function flyTo(coords){
	map.flyTo({	center: coords });
}


(function() { // search places
	
var results_count = 0
window.doSearch = function(value) {
	if(value.length < 3) return $('#map_search_results').html('')
	searchPlace(value, function(data) {
		if (data.length) {
			results_count = data.length
			var list = data.map(data => {
				return `<li class="eclips" title="${data.name}" data-lat="${data.latitude}" data-lng="${data.longitude}">${data.name}</li>`
			})
			$('#map_search_results').html(list)
		}
	})
}
$('#map_search_input').on('input', function(){ doSearch(this.value) })

$('#map_search_results').on('click', 'li',function() {
	log(this.innerHTML)
	searchResultMarker.setLngLat([this.dataset.lng, this.dataset.lat])
	$('#map_search_results').css('display', 'none')
	flyTo([this.dataset.lng, this.dataset.lat])
})

var activeIndex = -1
$('#map_search_input').on('keydown', function(e){
	if(e.which === 13 && $('#map_search_results li.active').length){
		e.stopPropagation()
		$('#map_search_results li.active')[0].click()
		$('#map_search_results').css('display', 'none')
		return false
	}
	$('#map_search_results li.active').removeClass('active')
	if (e.which === 38 || e.which === 40) {
		if (e.which === 38) { // arrow up
			activeIndex = in_range(activeIndex - 1, 0, results_count - 1)
		}
		if (e.which === 40) { // arrow down
			activeIndex = in_range(activeIndex + 1, 0, results_count - 1)
		}
		$('#map_search_results li').eq(activeIndex).addClass('active')
		// return false
	}else{
		activeIndex = -1
	}
})
function in_range(num, min, max) {
  if(num < min) return max
  if(num > max) return min
  return num
}

$('#map_search_input').focus(function(){
	// document.body.classList.remove('ajax-loader')
	$('#map_search_results').css('display', 'block')
})

$('body').on('click', function(e) {
  if (!$(e.target).closest('.map-search-wrapper').length && !$('#map_search_input:focus').length) {
  	// document.body.classList.add('ajax-loader')
	$('#map_search_results').css('display', 'none')
  }
})

}()) // search places

</script>

@include('modals.edit-files-modal')
@include('modals.edit-image-title')
@include('modals.edit-audio-title')


@endsection
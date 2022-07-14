<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Fly to a location</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.8.2/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.8.2/mapbox-gl.js"></script>
<style>
body { margin: 0; padding: 0; }
#map { position: absolute; top: 0; bottom: 0; width: 100%; }
</style>
</head>
<body>
<style>
#fly {
display: block;
position: relative;
margin: 0px auto;
width: 50%;
height: 40px;
padding: 10px;
border: none;
border-radius: 3px;
font-size: 12px;
text-align: center;
color: #fff;
background: #ee8a65;
}
</style>
<div id="map"></div>
<br>
<button id="fly">Fly</button>
<script>
	// TO MAKE THE MAP APPEAR YOU MUST
	// ADD YOUR ACCESS TOKEN FROM
	// https://account.mapbox.com
	mapboxgl.accessToken = 'pk.eyJ1Ijoic2xhdjAxIiwiYSI6ImNrMHd5Yjg0ZDAweWkzY295ZGx6MmpscDYifQ.I5L8kZNRpOOaRzNBMcJkfg';
const map = new mapboxgl.Map({
container: 'map',
style: 'mapbox://styles/mapbox/streets-v11',
center: [-74.5, 40],
zoom: 9
});
 
document.getElementById('fly').addEventListener('click', () => {
// Fly to a random location by offsetting the point -74.50, 40
// by up to 5 degrees.
map.flyTo({
center: [
-74.5 + (Math.random() - 0.5) * 10,
40 + (Math.random() - 0.5) * 10
],
// essential: true // this animation is considered essential with respect to prefers-reduced-motion
});
});
</script>
 
</body>
</html>
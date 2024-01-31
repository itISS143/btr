<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Map Example</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
        }
    </style>
</head>
<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map');

    // Add a tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Get the user's location and center the map
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;

            map.setView([userLat, userLng], 15); // Set the map center to the user's location

            // Add a marker at the user's location
            var userMarker = L.marker([userLat, userLng]).addTo(map)
                .bindPopup('You are here!')
                .openPopup();

            // Zoom to the marker with a smooth animation when clicked
            userMarker.on('click', function () {
                map.flyTo([userLat, userLng], 17, { duration: 3 }); // You can adjust the duration as needed
            });
        }, function (error) {
            console.error('Error getting user location:', error.message);
        });
    } else {
        console.error('Geolocation is not supported by this browser.');
    }
</script>

</body>
</html>

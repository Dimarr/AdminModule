<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <title>Last position of Client and Service Provider</title>
  <style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
      height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<?php
session_start();
$X1=@$_GET['X1'];
$Y1=@$_GET['Y1'];
$Y2=@$_GET['Y2'];
$X2=@$_GET['X2'];
$titlesp=@$_GET['tsp'];
$titleuser=@$_GET['tuser'];
?>
<body>
<div id="map"></div>
<script>

  function initMap() {

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 3,
      center: {lat: 31.77765, lng: 35.23547}
    });

    // Create an array of alphabetical characters used to label the markers.
    var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var titles = ['',''];
    titles[0] = <?php echo $titleuser?>;
    titles[1] = <?php echo $titlesp?>;
    //var color = BitmapDescriptorFactory.HUE_AZURE;
    // Add some markers to the map.
    // Note: The code uses the JavaScript Array.prototype.map() method to
    // create an array of markers based on a given "locations" array.
    // The map() method here has nothing to do with the Google Maps API.
    var markers = locations.map(function(location, i) {
      return new google.maps.Marker({
        position: location,
        label: labels[i % labels.length],
        title: titles[i]
      });
    });

    // Add a marker clusterer to manage the markers.
    var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
  }
  var locations = [
    {lat: <?php echo $X1 ?>, lng: <?php echo $Y1 ?>},
    {lat: <?php echo $X2 ?>, lng: <?php echo $Y2 ?>}
  ]
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmq1EPdfSqmruIF0n3ZoMgFMkQEfU8KMc&callback=initMap">
</script>
</body>
</html>
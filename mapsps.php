<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <title>Online Service Providers</title>
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
$ini_array = parse_ini_file("options.ini");
$link = mysqli_connect($ini_array["url"], $ini_array["user"], $ini_array["password"], $ini_array["database"]);
//echo $_SERVER['DOCUMENT_ROOT'];

if (!$link) {
    echo "Error: Impossible connect to MySQL." . PHP_EOL;
    echo "Error Code: " . mysqli_connect_errno() . PHP_EOL;
    echo "Details of error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$sql="SELECT phone,name,x,y FROM sproviders WHERE logined=1 AND busy=0 AND x is not null AND y is not null ORDER BY name ";
//    echo $sql.$where.$orderby;
$select=mysqli_query($link,$sql);
?>
<body>
<div id="map"></div>
<script>
    var locations = [];
    function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 3,
            center: {lat: 31.77765, lng: 35.23547}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var titles = [];
        <?php
            while ($row=mysqli_fetch_array($select))
            {
        ?>
            titles.push('<?php echo $row['name']." ".$row['phone'] ?>');
            locations.push({lat: <?php echo $row['x'] ?>, lng: <?php echo $row['y'] ?>})
        <?php }
            mysqli_close($link);
        ?>

        //titles[0] =
        //titles[1] =
        //var color = BitmapDescriptorFactory.HUE_AZURE;
        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function (location, i) {
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
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmq1EPdfSqmruIF0n3ZoMgFMkQEfU8KMc&callback=initMap">
</script>
</body>
</html>
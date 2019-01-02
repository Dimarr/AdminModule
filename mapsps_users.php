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

mysqli_query($link,"CALL showpairs");
$sql="SELECT spname,username,spphone,userphone, spx,spy,userx,usery FROM showpairs";
//    echo $sql.$where.$orderby;
$select=mysqli_query($link,$sql);
?>
<body>
<div id="map"></div>
<script>
    var locations = [];
    function initMap() {

        var pinImage;
        var markers;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: {lat: 31.77765, lng: 35.23547}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var images = [];
        var pinColor = ["C0C0C0","FF00FF","800080","800000","FFFF00","808000","00FF00","008000","00FFFF","008080","0000FF","000080",
            "FF00FF","FF00FF","BA55D3","9370DB","8A2BE2","9400D3","9932CC","8B008B","800080","4B0082","6A5ACD","483D8B","7FFFD4","006400"];

        for (i = 0; i < 26; i++) {
            pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor[i],
                new google.maps.Size(21, 34),
                new google.maps.Point(0,0),
                new google.maps.Point(10, 34));
/*            var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
                new google.maps.Size(40, 37),
                new google.maps.Point(0, 0),
                new google.maps.Point(12, 35)); */
            images.push(pinImage);
        }
        var titles = [];
        <?php
            while ($row=mysqli_fetch_array($select))
            {
        ?>
            titles.push('<?php echo "SP: ".$row['spname']." ".$row['spphone'] ?>');
            locations.push({lat: <?php echo $row['spx'] ?>, lng: <?php echo $row['spy'] ?>})

            titles.push('<?php echo "Customer: ".$row['username']." ".$row['userphone'] ?>');
            locations.push({lat: <?php echo $row['userx'] ?>, lng: <?php echo $row['usery'] ?>})
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
        markers = locations.map(function (location, i) {
            return new google.maps.Marker({
                position: location,
                label: labels[i % labels.length],
                title: titles[i],
                icon : images[Math.floor(i/2)]
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
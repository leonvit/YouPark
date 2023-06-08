<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
redirectIfNotLoggedIn();
$apiKey = "AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM";
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
      #map {
        height: 100%;
      }
    </style>
    
  </head>
  <body>

      <div id="navbar"></div>
  <script src="/nav/nav.js"></script>

  <h2 class="text-center display-5">Here are the available spots in your area:</h2>

  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <br>
        <div id="map" style="height: 500px;"></div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>"></script>
  <script>
    // Initialize the map
    var map = new google.maps.Map(document.getElementById('map'), {
      center: { lat: 37.9838, lng: 23.7275 },
      zoom: 10,
      disableDefaultUI: true
    });

    // Define a function to handle marker click events
    function markerClickHandler(marker, coord) {
      marker.addListener("click", function() {
        // Redirect to a page displaying the coordinates
        window.location.href = "search_wait.php?lat=" + coord[0] + "&lng=" + coord[1];
      });
    }

    // Call the PHP script and add markers to the map
    function coordinates() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var coordinates = JSON.parse(this.responseText);
        console.log(coordinates); // Log the coordinates to the console
        coordinates.forEach(function(coord) {
          var marker = new google.maps.Marker({
            position: new google.maps.LatLng(coord[0], coord[1]),
            map: map
          });
          // Add click event listener to the marker
          markerClickHandler(marker, coord);
        });
      }
    };
    xhttp.open("GET", "/php/get_location.php", true);
    xhttp.send();
  }
    coordinates()
    setInterval(coordinates, 2000);

  </script>
</body>
</html>

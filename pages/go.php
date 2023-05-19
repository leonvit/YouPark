<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
redirectIfNotLoggedIn();
$apiKey = "AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM";
$username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Go</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>

  
	  .slider-container {
      max-width: 80%;
      margin: 0 auto;
      padding: 20px;
    }

    .custom-range {
      height: 15px; /* Adjust the height as per your preference */
    }
    </style>
 
  
  </head>
  <body onload="initMap()">

  <div id="navbar"></div>
  <script src="/nav/nav.js"></script>
<br>

    <h4 class="text-center display-5">Register your Current Location as a Parking Spot:</h4>

    <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <br>
            <div id="map" style="height: 150px; width: 100%;"></div>
          </div>
      </div>
	  <br><br>
      <div class="row justify-content-center">
	  <div class="slider-container">
    <h4>Select Minutes You are Willing to Wait (Max 10):</h4><br>
    <input type="range" class="custom-range" id="minutesSlider" min="5" max="10" step="1" value="5">
    <h4 id="selectedMinutes" class="text-center">5 minutes</h4>
  </div>
  <div class="container text-center"><br>
      <button class="btn btn-primary btn-lg " id="save-location-btn">Register Parking Spot</button></div>
  <script>

      function initMap() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var myLatLng = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
			};

			var map = new google.maps.Map(document.getElementById('map'), {
				center: myLatLng,
				zoom: 18,
        disableDefaultUI: true
        
			});

			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: 'My Location'
			});

			// Add event listener to the "Register Parking Spot" button
			$("#save-location-btn").click(function() {
				saveLocation(myLatLng);
			});
		});
	} else {
		alert("Geolocation is not supported by this browser.");
	}
}
function saveLocation(position) {
	$.ajax({
		url: "/php/save_location.php",
		type: "POST",

		data: {
			lat: position.lat,
			lng: position.lng,
      exp: document.getElementById('minutesSlider').value,
      usr: "<?php echo $username; ?>"
		},
		success: function(response) {
      if (response=="Location saved successfully") {
        window.location.href = "go_wait.php?lat=" + position.lat + "&lng=" + position.lng;
      }
			

		},
		error: function(xhr, status, error) {
			alert("Error saving location: " + error);
		}
	});
	console.log(position.lat)
		console.log(position.lng)
}





    const minutesSlider = document.getElementById('minutesSlider');
    const selectedMinutes = document.getElementById('selectedMinutes');
    const submitBtn = document.getElementById('submitBtn');

    minutesSlider.addEventListener('input', function() {
      selectedMinutes.innerText = minutesSlider.value+ " minutes";
    });
  </script>
  

      
</div>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>"></script>

  </body>
</html>

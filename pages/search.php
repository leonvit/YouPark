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


  </head>
  <body>

      <div id="navbar"></div>
  <script src="/nav/nav.js"></script>

  <h2 class="text-center display-5" id="current-address">Searching for Spots</h2>
  <div class="text-center">
  <img src="https://assets-v2.lottiefiles.com/a/9b83b930-1182-11ee-8381-7f7d3c476078/KSI8iAB3lu.gif" style="max-width:40%;">
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <br>
      </div>
    </div>
  </div>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>

// Function to refresh the page when the user navigates back
function refreshPage() {
            location.reload(); // Reloads the current page
        }

        // Add an event listener to detect when the user navigates back
        window.addEventListener('popstate', function (event) {
            refreshPage();
        });
// Wrap your code in an event listener for the 'load' event of the window
window.addEventListener('load', function () {
  // Your code that uses the 'google' object goes here
  function updateCurrentAddress() {
    var addressElement = document.getElementById("current-address");

    // Check if Geolocation is supported by the browser
    if ("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(function (position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        // Use a reverse geocoding service to get the address based on coordinates
        var geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);

        geocoder.geocode({ location: latlng }, function (results, status) {
          if (status === "OK" && results.length > 0) {
            var userAddress = results[0].formatted_address;
            if (addressElement) {
              addressElement.innerText = "Searching for spots in " + userAddress;
            }
          } else {
            if (addressElement) {
              addressElement.innerText = "Unable to retrieve your current address.";
            }
          }
        });
      }, function (error) {
        // Handle any geolocation errors here
        if (addressElement) {
          addressElement.innerText = "Geolocation error: " + error.message;
        }
      });
    } else {
      // Geolocation is not supported by the browser
      if (addressElement) {
        addressElement.innerText = "Geolocation is not supported by your browser.";
      }
    }
  }

  // Call the function to update the current address
  updateCurrentAddress();
});


    // Function to calculate the distance between two sets of coordinates (in meters)
  function calculateDistance(lat1, lon1, lat2, lon2) {
  // Convert latitude and longitude from degrees to radians
  const deg2rad = (degrees) => {
    return degrees * (Math.PI / 180);
  };

  // Radius of the Earth in meters
  const R = 6371000; // Earth's radius in meters (approximately)

  // Convert latitude and longitude values to radians
  const radLat1 = deg2rad(lat1);
  const radLon1 = deg2rad(lon1);
  const radLat2 = deg2rad(lat2);
  const radLon2 = deg2rad(lon2);

  // Calculate the differences between the latitudes and longitudes
  const dLat = radLat2 - radLat1;
  const dLon = radLon2 - radLon1;

  // Haversine formula
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos(radLat1) * Math.cos(radLat2) * Math.sin(dLon / 2) ** 2;
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

  // Calculate the distance in meters
  const distance = R * c;

  return distance;
}


// Function to find the closest spot to the user's location within a list of spots
function findClosestSpot(userLat, userLon, spots) {
  // Initialize variables to store the closest spot and its distance
  let closestSpot = null;
  let closestDistance = Infinity;

  // Define the maximum allowed distance (500 meters)
  const maxDistance = 500;

  // Loop through the array of spots
  for (let i = 0; i < spots.length; i++) {
    const spot = spots[i];
    const spotLat = parseFloat(spot[0]); // Parse latitude as a float
    const spotLon = parseFloat(spot[1]); // Parse longitude as a float

    // Calculate the distance between the user and the current spot
    const distance = calculateDistance(userLat, userLon, spotLat, spotLon);

    // Check if the current spot is within the allowed distance
    if (distance <= maxDistance) {
      // If the current spot is closer than the previous closest spot, update the variables
      if (distance < closestDistance) {
        closestDistance = distance;
        closestSpot = spot;
      }
    }
  }

  return closestSpot;
}
// Function to continuously search for spots until one is found
// ...

// Function to continuously search for spots until one is found
function searchForSpots() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var spots = JSON.parse(this.responseText);
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          const userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };

          // Get the user's current location (assuming you have a function for this)
          var userLat = userLocation.lat;
          var userLon = userLocation.lng;
          //console.log(spots);
          // Find the closest spot to the user's location
          var closestSpot = findClosestSpot(userLat, userLon, spots);

          if (closestSpot) {
            console.log(closestSpot)
            // Extract latitude and longitude from closestSpot
            var closestLat = closestSpot[0];
            var closestLon = closestSpot[1];

            // Redirect to search_wait.php with the closest spot's coordinates
            console.log("search_wait.php?lat=" + closestLat + "&lng=" + closestLon);
            window.location.href = "search_wait.php?lat=" + closestLat + "&lng=" + closestLon;
          } else {
            // No spots found, continue searching
            searchForSpots();
          }
        });
      }
    }
  };
  xhttp.open("GET", "/php/get_location.php", true); // Replace with your endpoint to get spots
  xhttp.send();
}

// ...

window.onload = function () {
  searchForSpots();
};
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM&libraries=places"></script>

</body>
</html>

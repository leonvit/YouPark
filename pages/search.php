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

      <h2 class="text-center display-5" id="current-address">Here are the available spots</h2>
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

        function updateCurrentAddress() {
          var addressElement = document.getElementById("current-address");

          // Check if Geolocation is supported by the browser
          if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
              var latitude = position.coords.latitude;
              var longitude = position.coords.longitude;

              // Use a reverse geocoding service to get the address based on coordinates
              var geocoder = new google.maps.Geocoder();
              var latlng = new google.maps.LatLng(latitude, longitude);

              geocoder.geocode({ location: latlng }, function(results, status) {
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
            }, function(error) {
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

        // Call the function to update the current address and request permission
        function initialize() {
          updateCurrentAddress();
          requestLocationPermission();
        }

        // Function to request location permission
        function requestLocationPermission() {
          if ("geolocation" in navigator) {
            navigator.permissions.query({ name: 'geolocation' }).then(function(result) {
              if (result.state === 'granted') {
                // Permission already granted
                return;
              } else if (result.state === 'prompt') {
                // Permission not granted, ask the user for permission
                navigator.geolocation.getCurrentPosition(function() {
                  // Permission granted, do nothing
                }, function() {
                  // Permission denied, handle it if needed
                  alert("Location permission denied.");
                });
              } else if (result.state === 'denied') {
                // Permission denied, handle it if needed
                alert("Location permission denied.");
              }
            });
          }
        }

        // Function to calculate the distance between two sets of coordinates (in meters)
        function calculateDistance(lat1, lon1, lat2, lon2) {
          const R = 6371; // Earth's radius in kilometers
          const dLat = (lat2 - lat1) * (Math.PI / 180);
          const dLon = (lon2 - lon1) * (Math.PI / 180);
          const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * (Math.PI / 180)) *
              Math.cos(lat2 * (Math.PI / 180)) *
              Math.sin(dLon / 2) *
              Math.sin(dLon / 2);
          const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
          const distance = R * c * 1000; // Convert to meters
          return distance;
        }

        // Function to find the closest spot to the user's location
        function findClosestSpot(userLat, userLon, spots) {
          if (spots.length === 0) {
            return null; // No spots available
          }

          let closestSpot = null;
          let closestDistance = 500; // Initialize with a distance greater than 500 meters

          for (let i = 0; i < spots.length; i++) {
            const spot = spots[i];
            const distance = calculateDistance(
              userLat,
              userLon,
              spot.latitude,
              spot.longitude
            );

            if (distance < closestDistance && distance <= 500) {
              closestSpot = spot;
              closestDistance = distance;
            }
          }

          return closestSpot;
        }

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
                  var userLat = userLocation.lat; // Replace with your function to get user's latitude
                  var userLon = userLocation.lng; // Replace with your function to get user's longitude

                  var closestSpot = findClosestSpot(userLat, userLon, spots);

                  if (closestSpot) {
                    console.log("Closest spot:", closestSpot);
                    window.location.href = "search_wait.php?lat=" + closestSpot[0] + "&lng=" + closestSpot[1];
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

        // Start searching for spots when the page loads
        window.onload = function () {
          initialize();
        };

      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>

  </body>
</html>

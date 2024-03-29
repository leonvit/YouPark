<?php 
$latitude = floatval($_GET['lat']);
$longitude = floatval($_GET['lng']);
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';

$newname = $_SESSION['username'];


// Database connection details
$servername = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";
$conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE coordinates SET username2 = ? WHERE longitude = ? AND latitude = ?");
    $stmt->bind_param("sdd", $newname, $longitude, $latitude);
    $stmt->execute();
    $stmt->close();
    $conn->close();


?>

<!DOCTYPE html>
<html>
<head>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div id="navbar"></div>
  <script src="/nav/nav.js"></script><br>
  <div class="container mt-4 text-center">
    <button id="getDirectionsBtn" class="btn btn-primary">Get Directions</button><br><br>
    <button id="destinationReachedBtn" class="btn btn-success">I Have Reached My Destination</button>
  </div>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
      const lat = urlParams.get('lat');
      const lng = urlParams.get('lng');
    document.addEventListener('DOMContentLoaded', function() {
      const getDirectionsBtn = document.getElementById('getDirectionsBtn');
      const destinationReachedBtn = document.getElementById('destinationReachedBtn');

      // Get latitude and longitude from the URL
      

      // Check if latitude and longitude are present
      if (lat && lng) {
        // Build the Google Maps URL
        const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

        // Open the Google Maps link
        getDirectionsBtn.addEventListener('click', function() {
          window.open(mapsUrl, '_blank').focus();
        });
      } else {
        getDirectionsBtn.disabled = true;
        console.log('Latitude and longitude not found in the URL.');
      }

      // Handle destination reached button click
      destinationReachedBtn.addEventListener('click', function() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/php/pairing2.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response
                const urlParams = new URLSearchParams(window.location.search);
                const destinationLat = urlParams.get("lat");
                const destinationLng = urlParams.get("lng");
                window.location.href = "/pages/search_success2.php?lat=" + destinationLat + "&lng=" + destinationLng;
            }
        };

        // Serialize the data object as URL-encoded parameters
        var data = new URLSearchParams();
        data.append('lat', lat);
        data.append('lng', lng);

        xhr.send(data.toString());
      });
    });
    function updateLocation() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    // Extract latitude and longitude from the position object
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Send the data to the PHP script using an XMLHttpRequest
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "/php/give_live.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    // Prepare the data to send
                    const data = `latitude=${lat}&longitude=${lng}&user_lat=${latitude}&user_long=${longitude}`;

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            console.log("Location updated successfully");
                        }
                    };

                    // Send the request
                    xhr.send(data);
                });
            }
        }

        // Call the updateLocation function every 2 seconds
        setInterval(updateLocation, 2000); 
  </script>
</body>
</html>

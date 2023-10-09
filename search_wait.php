<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
redirectIfNotLoggedIn();
$apiKey = "AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM";
$username = $_SESSION['username'];

// Retrieve latitude and longitude from the URL
$lat = floatval($_GET['lat']);
$lng = floatval($_GET['lng']);

// Connect to the database
$servername = "server12.cretaforce.gr";
$db_username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";

$conn = new mysqli($servername, $db_username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve expiration date and username from the database
$stmt = $conn->prepare("SELECT expiration, username FROM coordinates WHERE longitude = ? AND latitude = ?");
$stmt->bind_param("dd", $lng, $lat);
$stmt->execute();
$stmt->store_result();
$error = "";
if ($stmt->num_rows === 0) {
    // No coordinates found in the database
    $error = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>Coordinates not found.</div>";
    window.location.href = "/";

} else {
    // Retrieve expiration date and username from the result
    $stmt->bind_result($expiration, $usr_username);
    $stmt->fetch();
    if ($expiration !== null) {
        // Calculate remaining time
        $currentTimestamp = time();
        $remainingTime = $expiration - $currentTimestamp;

        if ($remainingTime > 0) {
            $expiratonjs = $expiration * 1000;
            // Prepare the JavaScript code

            // Display the Bootstrap clock
        } else {
            // Delete the row from the database
            $deleteStmt = $conn->prepare("DELETE FROM coordinates WHERE longitude = ? AND latitude = ?");
            $deleteStmt->bind_param("dd", $lng, $lat);
            $deleteStmt->execute();
            $deleteStmt->close();
            $error = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>Expiration reached. Row deleted from the database.</div>";

        }
    } else {
        // No expiration found for the given coordinates
        $error = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>No expiration found for the given coordinates.</div>";

    }
}

$stmt->close();
// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Wait</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .countdown-container {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }

        .countdown-container:before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 190px;
            height: 190px;
            border-radius: 50%;
            border: 10px solid #ccc;
        }

        .countdown-container .countdown-clock {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
        }

        #map {
            height: 200px;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body onload=initMap()>
<div id="navbar"></div>
<script src="/nav/nav.js"></script>
<br>
<div id='error' class="text-center"><?php echo $error ?></div>
<h1 class="text-center">Closest Spot</h1><br>
<h2 id="distance" class="text-center"></h2>

<div id="map"></div><br>
<h3 class="text-center">Remaining Time:</h3>
<div class="countdown-container">
    <h3 id='countdownClock' class="countdown-clock"></h3>
</div><br>
<div class="container text-center">
    <button class="btn btn-primary" onclick="requestPark()">Park There (-50 COINS)</button>
</div>
<div id="error"></div>
<script>
    function calculateDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371 * 1000; // Radius of the Earth in meters (multiply by 1000 for meters)

    // Convert latitude and longitude from degrees to radians
    const lat1Rad = (lat1 * Math.PI) / 180;
    const lon1Rad = (lon1 * Math.PI) / 180;
    const lat2Rad = (lat2 * Math.PI) / 180;
    const lon2Rad = (lon2 * Math.PI) / 180;

    // Haversine formula
    const dLat = lat2Rad - lat1Rad;
    const dLon = lon2Rad - lon1Rad;

    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1Rad) * Math.cos(lat2Rad) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    // Calculate the distance in meters
    const distance = earthRadius * c;

    return distance;
}
    var countdownDate = new Date(<?php echo $expiration ?> * 1000).getTime();
    var countdownClock = document.getElementById('countdownClock');

    var x = setInterval(function () {
        var now = new Date().getTime();
        var distance = countdownDate - now;

        if (distance < 0) {
            clearInterval(x);
            countdownClock.innerHTML = 'EXPIRED';
            window.location.href = "/";

        } else {
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownClock.innerHTML = minutes + ':' + seconds;
        }
    }, 1000);


    function requestPark() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/php/pairing2b.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText != "error: Insufficient coin" && xhr.responseText !='error: Insufficient coins') {

            // Handle the response
            const urlParams = new URLSearchParams(window.location.search);
            const destinationLat = urlParams.get("lat");
            const destinationLng = urlParams.get("lng");
            window.location.href = "/pages/search_success1.php?lat=" + destinationLat + "&lng=" + destinationLng;
            }
            else {
                document.getElementById("error").innerHTML = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>"+xhr.responseText+"</div>";}

            }
        
        }

    // Serialize the data object as URL-encoded parameters
    var data = new URLSearchParams();
    data.append('lat', <?php echo $lat ?>);
    data.append('lng', <?php echo $lng ?>);

    xhr.send(data.toString());
};

function initMap() {
    // Get the user's current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };
            const parkingspot = { lat:<?php echo $lat?>, lng:<?php echo $lng?> };

            
            const centerLat = (userLocation.lat + parkingspot.lat) / 2;
            const centerLng = (userLocation.lng + parkingspot.lng) / 2;

            // Calculate the distance between the two locations using the Haversine formula
            const userLat = userLocation.lat;
            const userLng = userLocation.lng;
            const parkingLat = parkingspot.lat;
            const parkingLng = parkingspot.lng;

            // Calculate the distance in kilometers
            const distance = calculateDistance(userLat, userLng, parkingLat, parkingLng);
                        
            // Inside your initMap() function, after calculating the distance
            const distanceElement = document.getElementById('distance'); // Replace 'distance' with the ID of your HTML element
            const distanceInMeters = distance.toFixed(2); // Round the distance to two decimal places
            distanceElement.textContent = `Distance: ${distanceInMeters} meters`;

            // Calculate the zoom level based on the distance (adjust this as needed)
            const zoomLevel = Math.floor(15 - Math.log2(distance / 10)); // Adjust the divisor (10) as needed for desired zoom level

            
            
            
            
            // Create a map centered at the user's location
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: zoomLevel,
                 center: { lat: centerLat, lng: centerLng },
            });
            // Create markers for user's location and the parking spot
            const userMarker = new google.maps.Marker({
                position: userLocation,
                map: map,
                title: "Your Location",
            });

            const parkingMarker = new google.maps.Marker({
                position: parkingspot,
                map: map,
                title: "Parking",
            });

            // Create a DirectionsService object
            const directionsService = new google.maps.DirectionsService();
            const directionsDisplay = new google.maps.DirectionsRenderer({
                map: map,
                panel: document.getElementById("directionsPanel"),
            });

            // Create a LatLngBounds object to contain both markers
            

            // Create a request for directions
            const request = {
                origin: userLocation,
                destination: parkingspot,
                travelMode: google.maps.TravelMode.DRIVING, // You can change this to WALKING, BICYCLING, etc.
            };

            // Calculate directions
            directionsService.route(request, function (response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                } else {
                    console.error("Directions request failed due to " + status);
                }
            });
        });
    } else {
        console.error("Geolocation is not supported by your browser.");
    }
}

    // Invoke the function to initialize the map
</script>

<!-- Bootstrap JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey ?>"></script>
</body>
</html>

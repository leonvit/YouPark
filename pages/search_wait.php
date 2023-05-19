<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
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

        #mapSpot {
            height: 200px;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body onload=initMapSpot()>
<div id="navbar"></div>
<script src="/nav/nav.js"></script>
<br>
<div id='error' class="text-center"><?php echo $error ?></div>
<h1 class="text-center"><?php echo $usr_username ?>'s Parking Spot</h1><br>
<div id="mapSpot"></div><br>
<h3 class="text-center">Remaining Time:</h3>
<div class="countdown-container">
    <h3 id='countdownClock' class="countdown-clock"></h3>
</div><br>
<div class="container text-center">
    <button class="btn btn-primary" onclick="requestPark()">Park There</button>
</div>

<script>
    var countdownDate = new Date(<?php echo $expiration ?> * 1000).getTime();
    var countdownClock = document.getElementById('countdownClock');

    var x = setInterval(function () {
        var now = new Date().getTime();
        var distance = countdownDate - now;

        if (distance < 0) {
            clearInterval(x);
            countdownClock.innerHTML = 'EXPIRED';
        } else {
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownClock.innerHTML = minutes + ':' + seconds;
        }
    }, 1000);


    function requestPark() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/php/pairing2.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response
            window.location.href = "/pages/search_success.php";
        }
    };

    // Serialize the data object as URL-encoded parameters
    var data = new URLSearchParams();
    data.append('lat', <?php echo $lat ?>);
    data.append('lng', <?php echo $lng ?>);

    xhr.send(data.toString());
}


    function initMapSpot() {
        var parkingLatLng = {lat: <?php echo $lat ?>, lng: <?php echo $lng ?>};

        var mapSpot = new google.maps.Map(document.getElementById('mapSpot'), {
            center: parkingLatLng,
            zoom: 18,
            disableDefaultUI: true
        });

        var marker = new google.maps.Marker({
            position: parkingLatLng,
            map: mapSpot,
            title: 'Parking Spot'
        });
    }

    // Invoke the function to initialize the map
</script>

<!-- Bootstrap JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey ?>"></script>
</body>
</html>

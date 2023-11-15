<?php
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

// Retrieve expiration date from the database
$stmt = $conn->prepare("SELECT expiration FROM coordinates WHERE longitude = ? AND latitude = ?");
$stmt->bind_param("dd", $lng, $lat);
$stmt->execute();
$stmt->store_result();
$error = "";
if ($stmt->num_rows === 0) {
    // No coordinates found in the database
    $error = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>Coordinates not found.</div>";
} else {
    // Retrieve expiration date from the result
    $stmt->bind_result($expiration);
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
            border-radius: 50%;
            border: 2px solid #ccc;
        }

        .countdown-clock {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="navbar"></div>
  <script src="/nav/nav.js"></script>
<br>
<div id='error' class="text-center"><?php echo $error ?></div>
<h1 class="text-center">Waiting For a driver...</h1><br>
<div class="countdown-container">
<h3 id='countdownClock' class="countdown-clock"></h3>
</div><br>
<div class="container text-center">
    <button class="btn btn-danger" onclick="deleteRow()">Stop Waiting</button>

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
            window.location.href = "/pages/go.php";

  
        } else {
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownClock.innerHTML = minutes + ':' + seconds;
        }
    }, 1000);


    function deleteRow() {
    var confirmDelete = confirm("Are you sure you want to stop waiting?");
    if (confirmDelete) {
        // Send an AJAX request to delete the row from the database
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                window.location.replace("go.php");
            }
        };
        xmlhttp.open("GET", "/php/delete_row.php?lat=<?php echo $lat ?>&lng=<?php echo $lng ?>", true);
        xmlhttp.send();
    }
}
function fetchData() {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "/php/pairing1.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Check if the response is "success"
      if (xhr.responseText === "success") {
        const urlParams = new URLSearchParams(window.location.search);
            const destinationLat = urlParams.get("lat");
            const destinationLng = urlParams.get("lng");
            window.location.href = "/pages/go_success1.php?lat=" + destinationLat + "&lng=" + destinationLng;
      }
      
    }
  };

  // Serialize the data object as URL-encoded parameters
  var data = new URLSearchParams();
  data.append('lat', <?php echo $lat ?>);
  data.append('lng', <?php echo $lng ?>);

  xhr.send(data.toString());

  // Fetch data again after 1 second
  setTimeout(fetchData, 1000);
}



// Initial data fetch
fetchData();
</script>
</body>
</html>

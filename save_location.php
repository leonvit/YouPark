<?php
// get the marker coordinates from the query string
$lat = $_POST['lat'];
$lng = $_POST['lng'];

// connect to the database
$servername = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// prepare and bind the insert statement
$stmt = $conn->prepare("INSERT INTO coordinates (longitude, latitude) VALUES (?, ?)");
$stmt->bind_param("dd", $lng, $lat);

// execute the insert statement
if ($stmt->execute()) {
    echo "Location saved successfully";
} else {
    echo "Error: " . $stmt->error;
}

// close the statement and connection
$stmt->close();
$conn->close();

?>


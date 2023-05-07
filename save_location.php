<?php
// get the marker coordinates from the query string
$lat = $_GET['lat'];
$lng = $_GET['lng'];

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

// insert the marker coordinates into the database
$sql = "INSERT INTO coordinates (longitude, latitude) VALUES ('$lng', '$lat')";

if ($conn->query($sql) === TRUE) {
    echo "Location saved successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

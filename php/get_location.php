<?php
// Connect to the MySQL database
$servername = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Retrieve the coordinates from the database where username2 is NULL
$sql = "SELECT latitude, longitude FROM coordinates WHERE username2 IS NULL";
$result = $conn->query($sql);

// Format the coordinates into an array
$coordinates = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $coord = array($row["latitude"], $row["longitude"]);
    array_push($coordinates, $coord);
  }
}

// Close the database connection
$conn->close();

// Return the coordinates as JSON
echo json_encode($coordinates);
?>

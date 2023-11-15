<?php
// Replace these with your actual database credentials
$hostname = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$database = "tasos_db";

// Create a connection to the database
$conn = new mysqli($hostname, $username, $password, $database);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$user_lat = $_POST['user_lat'];
$user_long = $_POST['user_long'];

// Update the row in the "coordinates" table
$sql = "UPDATE coordinates 
        SET user_lat = ?, user_long = ? 
        WHERE latitude = ? AND longitude = ?";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error in SQL statement: " . $conn->error);
}

// Bind the parameters
$stmt->bind_param("dddd", $user_lat, $user_long, $latitude, $longitude);

// Execute the query
if ($stmt->execute()) {
    echo "Update successful";
} else {
    echo "Update failed: " . $stmt->error;
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>

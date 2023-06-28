<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$servername = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current timestamp
$currentTimestamp = time();

// Delete expired locations
$expirationTimestamp = $currentTimestamp + 1200; // Add 20 minutes (1200 seconds) to the current timestamp
$deleteStmt = $conn->prepare("DELETE FROM coordinates WHERE (Completed IS NULL AND Completed2 IS NULL AND username2 IS NULL AND expiration < ?) OR (expiration < ? AND (Completed IS NOT NULL OR username2 IS NOT NULL OR Completed2 IS NOT NULL))");
$deleteStmt->bind_param("ii", $currentTimestamp, $expirationTimestamp);
$deleteStmt->execute();
$deleteStmt->close();

// Close the connection
$conn->close();

echo "Expired locations and specified conditions deleted successfully";
?>
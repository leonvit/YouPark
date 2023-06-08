<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// get the marker coordinates from the query string
$lat = floatval($_POST['lat']);
$lng = floatval($_POST['lng']);
$exp = (int)$_POST['exp'];
$usr = $_POST['usr'];
$expirationTime = time() + ($exp * 60);

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

// Check if coordinates already exist and delete them
$deleteStmt = $conn->prepare("DELETE FROM coordinates WHERE longitude = ? AND latitude = ?");
$deleteStmt->bind_param("dd", $lng, $lat);
$deleteStmt->execute();
$deleteStmt->close();

// Insert new coordinates
$insertStmt = $conn->prepare("INSERT INTO coordinates (longitude, latitude, expiration, username) VALUES (?, ?, ?, ?)");
$insertStmt->bind_param("ddds", $lng, $lat, $expirationTime, $usr);

if ($insertStmt->execute()) {
    echo "Location saved successfully";
} else {
    echo "Error: " . $insertStmt->error;
}
$coins=50;
$stmt = $conn->prepare("UPDATE users SET coins = coins + ? WHERE username = ?");
$stmt->bind_param("is", $coins, $usr);
$stmt->execute();
if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
}
$stmt->close();


// close the statements and connection
$insertStmt->close();
$conn->close();
?>

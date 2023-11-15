<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
redirectIfNotLoggedIn();

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

// Prepare the SQL statement to delete the row
$stmt = $conn->prepare("DELETE FROM coordinates WHERE longitude = ? AND latitude = ?");
$stmt->bind_param("dd", $lng, $lat);

$response = '';

// Execute the statement
if ($stmt->execute()) {
    $response = 'success'; // Row deleted successfully
} else {
    $response = 'error'; // Error occurred while deleting the row
}

$stmt->close();
$conn->close();

echo $response; 
?>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
redirectIfNotLoggedIn();
$apiKey = "AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM";

$user = $_SESSION['username'];
// Retrieve the latitude and longitude values from POST
$lat = floatval($_POST['lat']);
$lng = floatval($_POST['lng']);

// Establish a connection to your MySQL database
$servername = "server12.cretaforce.gr";
$db_username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";

$conn = new mysqli($servername, $db_username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("UPDATE coordinates SET Completed = 1 WHERE longitude = ? AND latitude = ?");
$stmt->bind_param("dd", $lng, $lat);
    
    if ($stmt->execute()) {
        $response = 'success'; // Row updated successfully
    } else {
        $response = 'error'; // Error occurred while updating the row
    }
    
    $stmt->close();

echo $response;
?>

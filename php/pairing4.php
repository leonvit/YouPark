<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
$usr = $_SESSION['username'];
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

$stmt = $conn->prepare("UPDATE coordinates SET Completed2 = 1 WHERE longitude = ? AND latitude = ?");
$stmt->bind_param("dd", $lng, $lat);

$response = '';

// Execute the statement
if ($stmt->execute()) {
    $response = 'success'; // Row updated successfully
} else {
    $response = 'error'; // Error occurred while updating the row
}


$coins=50;
$addition = $conn->prepare("UPDATE users SET coins = coins + ? WHERE username = ?");
$addition->bind_param("is", $coins, $usr);
$addition->execute();
if (!$stmt->execute()) {
    echo "Error: " . $addition->error;
}


$addition->close();
$stmt->close();

$conn->close();

echo $response;
?>

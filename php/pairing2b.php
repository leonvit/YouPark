<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
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

// Retrieve the number of coins for the username
$stmt = $conn->prepare("SELECT coins FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($coins);
$stmt->fetch();
$stmt->close();

$response = '';

if ($coins >= 50) {
    // Subtract 50 coins from the user's balance
    $newCoins = $coins - 50;
    
    $stmt = $conn->prepare("UPDATE users SET coins = ? WHERE username = ?");
    $stmt->bind_param("is", $newCoins, $user);
    $stmt->execute();
    
    $stmt->close();
    
    // Execute the statement
    $stmt = $conn->prepare("UPDATE coordinates SET Completed = 1 WHERE longitude = ? AND latitude = ?");
    $stmt->bind_param("dd", $lng, $lat);
    
    if ($stmt->execute()) {
        $response = 'success'; // Row updated successfully
    } else {
        $response = 'error'; // Error occurred while updating the row
    }
    
    $stmt->close();
} else {
    $response = 'error: Insufficient coins'; // Error: Insufficient coins
}

$conn->close();

echo $response;
?>

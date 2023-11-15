<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
redirectIfNotLoggedIn();
$apiKey = "AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM";

$user = $_SESSION['username'];

$servername = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";

// Create a new MySQLi object
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$stmt = $conn->prepare("SELECT coins FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();

// Bind the result to a variable
$stmt->bind_result($coinCount);
$stmt->fetch();
$coins = $coinCount;

// Close the statement
$stmt->close();

// Add 50 coins to the existing coin count
$coins += 50;

// Update the user's coin count in the database
$stmt = $conn->prepare("UPDATE users SET coins = ? WHERE username = ?");
$stmt->bind_param("ss", $coins, $user);
$stmt->execute();

// Close the statement and connection
$stmt->close();
$conn->close();

// Redirect to the root ("/") URL
header("Location: /");
exit();
?>

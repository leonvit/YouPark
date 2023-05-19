<?php
$expirationDate = date('Y-m-d'); // Set the expiration date you want to delete rows for

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

// Prepare the SQL statement to delete rows with the specified expiration date
$stmt = $conn->prepare("DELETE FROM coordinates WHERE expiration = ?");
$stmt->bind_param("s", $expirationDate);

// Execute the statement
if ($stmt->execute()) {
    echo "Rows deleted successfully.";
} else {
    echo "Error occurred while deleting rows.";
}

$stmt->close();
$conn->close();
?>

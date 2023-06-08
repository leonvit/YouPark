<?php
// Retrieve the POSTed longitude and latitude
$lng = $_POST['lng'];
$lat = $_POST['lat'];

// Connect to the MySQL database
$servername = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "SELECT Completed2 FROM coordinates WHERE longitude = '$lng' AND latitude = '$lat'";

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Check if there is a row with the given coordinates
    if ($result->num_rows > 0) {
        // Fetch the row data
        $row = $result->fetch_assoc();

        // Check if the Completed column is true
        if ($row['Completed2'] == 1) {
            echo "success";
            $stmt = $conn->prepare("UPDATE coordinates SET Completed2 = 0 WHERE longitude = ? AND latitude = ?");
            $stmt->bind_param("dd", $lng, $lat);
            $stmt->execute();
        }
    }
}

// Close the database connection
$conn->close();
?>
